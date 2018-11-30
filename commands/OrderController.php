<?php
/**
 * Created by PhpStorm.
 * User: joe
 * Date: 2018/8/11
 * Time: 13:08
 * 订单相关自动化脚本
 */

namespace app\commands;

use app\models\Forward;
use app\models\OrderRecord;
use app\models\Orders;
use app\models\ShippingApi;
use app\models\Stocks;
use app\models\Replenishment;
use app\models\OrdersItem;
use yii\console\Controller;
use yii\db\ActiveRecord;
use Yii;


class OrderController extends Controller
{

    /**
     * 已确认的订单查询是否可发货
     * @throws \yii\db\Exception
     */
    public function actionCheckStock()
    {
        $shipping_model = new ShippingApi();
        $repModel = new Replenishment();
        //所有已确认订单
        $orders = Orders::find()->where(['status' => 2])->orderBy('id ASC')->all();
        $arr2 = [];
        foreach($orders as $order){
            $arr2[] = $order->id;
        }
//        print_r($arr2);

        $stockModel = new Stocks();
        //查出所有的未发货的订单
        foreach($arr2 as $k=>$v){
            //是否匹配到转寄仓
            $res_forward = Forward::order_forward_warehouse($v);
            if ($res_forward)
            {
                continue;
            }
            //进行非正常转寄仓匹配
            $res_forward_abnormal = Forward::order_forward_warehouse_abnormal($v);
            if ($res_forward_abnormal && Stocks::change_order_operated($v))
            {
                continue;
            }
            //进行非正常库存匹配
            $res_stock_abnormal = Stocks::sku_stock_match($v);
            if ($res_stock_abnormal && Stocks::change_order_operated($v))
            {
                continue;
            }
            $item = OrdersItem::find()->where(['order_id'=>$v])->all();
            $status=[];
            $sku_qty = [];
            foreach ($item as $v3) {
                if(isset($sku_qty[$v3->sku]))
                {
                    $sku_qty[$v3->sku]['qty'] += $v3->qty;//相同SKU合计数量
                }else{
                    $sku_qty[$v3->sku] = [
                        'qty' => $v3->qty,
                        'id' => $v3->id
                    ];
                }
            }
            foreach($sku_qty as $sku=>$sku_data)
            {
                //可用库存
                $number = $stockModel->inventoryBySku($sku);
                //在途库存
                $ztnumber = $stockModel->transitInventoryBySku($sku);
                //已采购与待采购订单SKU量
                $ycgnumber = $stockModel->countPurchaseTotalBySku($sku);
                //中间表未采购
                $rep_trans = $stockModel->repTransBySku($sku);
                //补采数量
                $rep = $number + $ztnumber + $rep_trans -  $ycgnumber - $sku_data['qty'];
                echo $sku.':'.$rep;
                echo "\n\r";
                if($rep >= 0)
                {
                    if($number < $sku_data['qty'])
                    {
                        $status[] = 2;
                        $ordersData = Orders::findOne($v);
                        $ordersData->status = 21;//备货在途
                        $transaction = ActiveRecord::getDb()->beginTransaction();
                        try{
                            if (!$ordersData->save() || !OrderRecord::addRecord($v,21,4,'执行匹配库存脚本', 1))
                            {
                                $transaction->rollBack();
                            }
                            $transaction->commit();
                        }catch (\Exception $e) {
                            $transaction->rollBack();
                        }
                        $repModel->setIsNewRecord(true);
                        unset($repModel->id);
                    }else{
                        $status[] = 1;
                    }
                }else{
                    $status[] = 0;
                    //采购数量写入中间表
                    $repModel->attributes = [
                        'orders_id'=>$v,
                        'sku_id'=>$sku,
                        'create_time'=>date("Y-m-d H:i:s"),
                        'supplement_number' => abs($rep),
                    ];
                    $repModel->setIsNewRecord(true);
                    unset($repModel->id);
                    $repModel->save();
                }
            }
//            print_r($status);

            if(in_array(0, $status))
            {
                $ordersData = Orders::findOne($v);
                $ordersData->status = 20;//待采购
                $transaction = ActiveRecord::getDb()->beginTransaction();
                try{
                    if (!$ordersData->save() || !OrderRecord::addRecord($v,20,4,'执行匹配库存脚本', 1))
                    {
                        $transaction->rollBack();
                    }
                    $transaction->commit();
                }catch (\Exception $e) {
                    $transaction->rollBack();
                }
            }elseif(in_array(2, $status)){
                //不做操作
            }else{
                $ordersData = Orders::findOne($v);
                //超时单的判断
                $diff_hours = (time()-strtotime($ordersData->create_date))/3600;
                $status = $diff_hours>120?12:7;
                $ordersData->status = $status;//待发货
//                $ordersData->on_shipping_time = date('Y-m-d H:i:s');
                $transaction = ActiveRecord::getDb()->beginTransaction();
                try{
                    if (!$ordersData->save() || !OrderRecord::addRecord($v, $status, 4, '执行匹配库存脚本', 1))
                    {
                        $transaction->rollBack();
                    }
                    if ($status == 7)
                    {
                        $shipping_model->distribution_shipping($v);
                    }
                    $transaction->commit();
                }catch (\Exception $e) {
                    print_r($e);
                    $transaction->rollBack();
                    exit();
                }
            }

        }
    }

    /**
     * 处理 已采购与备货在途订单
     * 检查库存更新订单状态为可发货
     */
    public function actionCheckOrderStock()
    {
        $shipping_model = new ShippingApi();
        $model = new Orders();
        $stockModel = new Stocks();
        $orders = $model->find()->where(['in', 'status', ['3', '21']])->orderBy('id ASC')->all();
        foreach ($orders as $order) {
            $stock = true;
            $sku_qty = [];
            foreach ($order->getItems($order->id) as $item) {
                if(trim($item->sku))
                {
                    if(isset($sku_qty[$item->sku]))
                    {
                        $sku_qty[$item->sku] += $item->qty;//相同SKU合计数量
                    }else{
                        $sku_qty[$item->sku] = $item->qty;
                    }

                    if ($stock) {
                        $stock = $stockModel->checkStock($item->sku, $sku_qty[$item->sku]);
                    }
                }

            }

            if ($stock) {
                //超时单的判断
                $diff_hours = (time()-strtotime($order->create_date))/3600;
                $status = $diff_hours>120?12:7;
                echo $order->id."：";
                $order->status = $status;//待发货或超时单
                OrderRecord::addRecord($order->id, $status, 4, '执行匹配库存脚本', 1);
//                $order->on_shipping_time = date('Y-m-d H:i:s');
                echo $order->save();
                if ($status == 7)
                {
                    $shipping_model->distribution_shipping($order->id);
                }
                echo "\n";
            }
        }

        sleep(2);   //停顿2秒
        //执行已确认订单脚本
        $this->check_stock();
    }

    /**
     * 从采购需求表更新订单到已采购
     */
    public function actionUpdateOrderForTag()
    {
        $tr = Yii::$app->db->beginTransaction();
        $datas = Yii::$app->db->createCommand("SELECT * FROM replenishment WHERE update_tag=0 AND `status`='已采购'")->queryAll();
        foreach ($datas as $data)
        {
            $order = Orders::find()->where(['id' => $data['orders_id']])->andWhere(['status' => 20])->one();
            if($order && 20 == $order->status)
            {
                $order->status = 3;
                if($order->save() && Yii::$app->db->createCommand("UPDATE replenishment SET update_tag=1 WHERE id=:id", [':id' => $data['id']])->execute() && OrderRecord::addRecord($order->id,3,4,'从采购需求表更新订单到已采购',1))
                {
                    echo 1;
                }else{
                    $tr->rollBack();
                }
            }
        }
        $tr->commit();

    }

    /**
     * 更新采购单为已收货
     */
    public function actionUpdatePurchaseForIn()
    {
        //获取三天前创建的采购单的已确认订单
        $purchase_arr = Yii::$app->db->createCommand("select order_number from purchases where status = 2 and create_time< date_sub(curdate(),interval 3 day)")->queryAll();
        if ($purchase_arr)
        {
            foreach ($purchase_arr as $purchase)
            {
                $purchase_item = Yii::$app->db->createCommand("select * from purchase_items where purchase_number = '".$purchase['order_number']."'")->queryAll();
                $flag = 1;
                if (!$purchase_item)
                {
                    continue;
                }
                foreach ($purchase_item as $key => $value)
                {
                    if ($value['qty'] > $value['delivery_qty'])
                    {
                        $flag = 0;
                        break;
                    }
                }
                if ($flag)
                {
                    Yii::$app->db->createCommand("update purchases set status = 3 where order_number = '".$purchase['order_number']."'")->execute();
                }
            }
        }
    }


    public function check_stock()
    {
        $shipping_model = new ShippingApi();
        $repModel = new Replenishment();
        //所有已确认订单
        $orders = Orders::find()->where(['status' => 2])->orderBy('id ASC')->all();
        $arr2 = [];
        foreach($orders as $order){
            $arr2[] = $order->id;
        }
//        print_r($arr2);

        $stockModel = new Stocks();
        //查出所有的未发货的订单
        foreach($arr2 as $k=>$v){
            //是否匹配到转寄仓
            $res_forward = Forward::order_forward_warehouse($v);
            if ($res_forward)
            {
                continue;
            }
            //进行库存匹配
            $res_stock = Stocks::check_stock_by_id($v);
            if ($res_stock)
            {
                continue;
            }
            //进行非正常转寄仓匹配
            $res_forward_abnormal = Forward::order_forward_warehouse_abnormal($v);
            if ($res_forward_abnormal && Stocks::change_order_operated($v))
            {
                continue;
            }
            //进行非正常库存匹配
            $res_stock_abnormal = Stocks::sku_stock_match($v);
            if ($res_stock_abnormal && Stocks::change_order_operated($v))
            {
                continue;
            }
            $item = OrdersItem::find()->where(['order_id'=>$v])->all();
            $status=[];
            $sku_qty = [];
            foreach ($item as $v3) {
                if(isset($sku_qty[$v3->sku]))
                {
                    $sku_qty[$v3->sku]['qty'] += $v3->qty;//相同SKU合计数量
                }else{
                    $sku_qty[$v3->sku] = [
                        'qty' => $v3->qty,
                        'id' => $v3->id
                    ];
                }
            }
            foreach($sku_qty as $sku=>$sku_data)
            {
                //可用库存
                $number = $stockModel->inventoryBySku($sku);
                //在途库存
                $ztnumber = $stockModel->transitInventoryBySku($sku);
                //已采购与待采购订单SKU量
                $ycgnumber = $stockModel->countPurchaseTotalBySku($sku);
                //中间表未采购
                $rep_trans = $stockModel->repTransBySku($sku);
                //补采数量
                $rep = $number + $ztnumber + $rep_trans -  $ycgnumber - $sku_data['qty'];
                echo $sku.':'.$rep;
                echo "\n\r";
                if($rep >= 0)
                {
                    if($number < $sku_data['qty'])
                    {
                        $status[] = 2;
                        $ordersData = Orders::findOne($v);
                        $ordersData->status = 21;//备货在途
                        $transaction = ActiveRecord::getDb()->beginTransaction();
                        try{
                            if (!$ordersData->save() || !OrderRecord::addRecord($v,21,4,'执行匹配库存脚本', 1))
                            {
                                $transaction->rollBack();
                            }
                            $transaction->commit();
                        }catch (\Exception $e) {
                            $transaction->rollBack();
                        }
                        $repModel->setIsNewRecord(true);
                        unset($repModel->id);
                    }else{
                        $status[] = 1;
                    }
                }else{
                    $status[] = 0;
                    //采购数量写入中间表
                    $repModel->attributes = [
                        'orders_id'=>$v,
                        'sku_id'=>$sku,
                        'create_time'=>date("Y-m-d H:i:s"),
                        'supplement_number' => abs($rep),
                    ];
                    $repModel->setIsNewRecord(true);
                    unset($repModel->id);
                    $repModel->save();
                }
            }
//            print_r($status);

            if(in_array(0, $status))
            {
                $ordersData = Orders::findOne($v);
                $ordersData->status = 20;//待采购
                $transaction = ActiveRecord::getDb()->beginTransaction();
                try{
                    if (!$ordersData->save() || !OrderRecord::addRecord($v,20,4,'执行匹配库存脚本', 1))
                    {
                        $transaction->rollBack();
                    }
                    $transaction->commit();
                }catch (\Exception $e) {
                    $transaction->rollBack();
                }
            }elseif(in_array(2, $status)){
                //不做操作
            }else{
                $ordersData = Orders::findOne($v);
                //超时单的判断
                $diff_hours = (time()-strtotime($ordersData->create_date))/3600;
                $status = $diff_hours>120?12:7;
                $ordersData->status = $status;//待发货
//                $ordersData->on_shipping_time = date('Y-m-d H:i:s');
                $transaction = ActiveRecord::getDb()->beginTransaction();
                try{
                    if (!$ordersData->save() || !OrderRecord::addRecord($v, $status, 4, '执行匹配库存脚本', 1))
                    {
                        $transaction->rollBack();
                    }
                    if ($status == 7)
                    {
                        $shipping_model->distribution_shipping($v);
                    }
                    $transaction->commit();
                }catch (\Exception $e) {
                    print_r($e);
                    $transaction->rollBack();
                    exit();
                }
            }

        }
    }

}
