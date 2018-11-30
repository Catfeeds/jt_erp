<?php
namespace app\controllers;

use app\models\Forward;
use app\models\GetShippingNo;
use app\models\JNTShippingApi;
use app\models\OrderStatusChange;
use app\models\ShippingApi;
use Yii;
use mPDF;
use app\models\OrderRecord;
use app\models\OrdersItemSearch;
use app\models\ProductsBase;
use app\models\ProductsVariant;
use app\models\Websites;
use app\models\Orders;
use app\models\OrdersItem;
use app\models\User;
use app\models\LocationStock;
use app\models\LocationLog;

use app\models\OrdersSearch;
use yii\db\ActiveRecord;
use yii\web\Controller;

/**
 * 客服操作订单，取消订单，
 * jieson 2018.11.08
 */
class ReplaceController extends Controller
{
    // 修改订单
    public function actionReplace()
    {
        $orderModel = Orders::find()->where(['id' => Yii::$app->request->get('id')])->one();
        $orderItems = OrdersItem::find()->where(['order_id' => Yii::$app->request->get('id')])->asArray()->all();

        foreach ($orderItems as $k => $item) {
            $sql = "select pb.title,pb.spu from products_variant pv left join products_base pb on pb.spu = pv.spu where pv.sku ='{$item['sku']}'";
            $res = Yii::$app->db->createCommand($sql)->queryOne();
            $orderItems[$k]['title'] = $res['title'];
            $orderItems[$k]['spu']   = $res['spu'];
        }
        return $this->renderpartial('/orders/replace', [
            'model' => $orderModel,
            'orderItems' => $orderItems
        ]);
    }
    
    // 添加sku
    public function actionAddSku()
    {
        $productModel = new ProductsVariant();
        $sku = trim(Yii::$app->request->get('sku'));
        $qty = trim(Yii::$app->request->get('qty'));
        $price = trim(Yii::$app->request->get('price'));
        $id = Yii::$app->request->get('id');

        $sku_data = ProductsVariant::find()->where(['sku'=> $sku])->asArray()->one();
        $spu_data = ProductsBase::find()->where(['spu' => $sku_data['spu']])->asArray()->one();
        if ($sku_data) {
            $sku_data['title'] = $spu_data['title'];
            $sku_data['qty']   = $qty;
            $sku_data['price'] = $price;
            return json_encode($sku_data);
        } else {
            return 0;
        }
    }

    // 保存修改的sku
    public function actionSaveSku()
    {
        $postData   = Yii::$app->request->get('postData');
        $order_id   = Yii::$app->request->get('order_id');
        $notes      = trim(Yii::$app->request->get('notes'));
        $editReason = trim(Yii::$app->request->get('editReason'));
        $insertData = [];

        $order = Orders::find()->where(['id' => $order_id])->one();
        // 状态判断，该状态下得不能修改
        if (in_array($order->status, [4,5,6,8,9,13,14,15])) {
            return -1;exit;
        }
        
        $newSku = '';
        foreach ($postData as $k => $data) {
            $data = json_decode($data);
            $insertData[$k]['order_id'] = $order_id;
            $insertData[$k]['sku']      = $data->sku;
            $insertData[$k]['qty']      = $data->qty;
            $insertData[$k]['price']    = $data->price;
            $insertData[$k]['color']    = $data->color;
            $insertData[$k]['size']     = $data->size;
            $insertData[$k]['image']    = $data->image;

            $newSku.=$data->sku.'+';
        }
    
        // 原来sku
        $oldSku = implode('+', array_column(OrdersItem::find()->where(['order_id' => $order_id])->select('sku')->asArray()->all(), 'sku'));
        // 新sku 
        $newSku = substr($newSku, 0, -1);
        // 备注信息
        $comment_u = $editReason.',订单原状态为:'.$order->status_array[$order->status].','.$notes.','."(系统备注-原sku:{$oldSku}, 现sku:{$newSku})";

        $tr = Yii::$app->db->beginTransaction();
        try {
            // 删除之前的items
            $del = OrdersItem::deleteAll(['order_id' => $order_id]);
            if ($del) {
                // 新增items
                $res = Yii::$app->db->createCommand()->batchInsert(OrdersItem::tableName(), ['order_id', 'sku', 'qty', 'price', 'color', 'size', 'image'], $insertData)->execute();
                if ($res) {
                    // 改变订单状态，变为已确认2 以及添加备注，订单号尾号加1
                    $order->status = $order->status == 1?1:2;
                    $order->comment_u = $comment_u.'——'.$order->comment_u;
                    $order->order_no = $order->order_no.'B';
                    $order->update_time = date('Y-m-d H:i:s', time());
                    if ($order->save() && OrderRecord::addRecord($order_id, $order->status, 2, '编辑订单')) {
                        $tr->commit();
                        $this->calculateTotal($order_id);
                        return 1;
                    }
                    
                }
            }
            $tr->rollback();
            return 0;
        } catch (Exception $e) {
            $tr->rollback();
            return 0;
        }
    }
    
    // 重新计算订单的金额
    public function calculateTotal($order_id)
    {
        $order = Orders::find()->where(['id' => $order_id])->one();
        $ordersItem = $order->getItems($order_id);
        
        $qty = $total = 0;
        foreach ($ordersItem as $item) {
            $total+= ($item['qty'] * $item['price']);
            if ($item['price'] != 0 || $item['price'] != 0.00) {
                $qty+= $item['qty'];
            }
        }
        $order->qty   = $qty;
        $order->total = $total;
        $order->save();
    }

    // 取消订单
    public function actionCancleOrder()
    {
        $order_id = Yii::$app->request->post('id');
        $notes = trim(Yii::$app->request->post('notes'));
        if (empty($notes)) {return json_encode(['status' => 0, 'msg' => sprintf('订单ID:%s,取消失败，取消原因不能为空！', $order_id)]);exit; }
        
        $orderModel = Orders::find()->where(['id' => $order_id])->one();
        // 不用管库存的
        $status_arr1 = [1,2,3,7,20,21,12,16];
        // 管库存的
        $status_arr2 = [4,8,13];
        $tr = Yii::$app->db->beginTransaction();
        if (in_array($orderModel->status, $status_arr1)) {

            $orderModel->status = 10;
            $orderModel->comment_u = $notes;

            if ($orderModel->save() && OrderRecord::addRecord($order_id,$orderModel->status,4,'取消订单')){
                $tr->commit();
                return json_encode(['status' => 1, 'msg' => sprintf('订单ID:%s,取消成功！', $order_id)]);
            } else {
                $tr->rollerback();
                return json_encode(['status' => 0, 'msg' => sprintf('订单ID:%s,取消失败，请稍候重试！', $order_id)]);exit;
            }
        } else if (in_array($orderModel->status, $status_arr2)) {
            return json_encode(['status' => 2, 'msg' => sprintf('订单ID:%s,该订单已发货或已转运，请确认是否已追回?', $order_id)]);exit;
        } else {
            return json_encode(['status' => 0, 'msg' => sprintf('订单ID:%s,取消失败，该订单状态下取消失败，请联系技术人员。', $order_id)]);exit;
        }
    }

    // 需返回库存的取消订单，已发货，打包，的订单
    public function actionCancleOrderSended()
    {
        $order_id = Yii::$app->request->post('id');
        $notes = trim(Yii::$app->request->post('notes'));
        if (empty($notes)) {return json_encode(['status' => 0, 'msg' => sprintf('订单ID:%s,取消失败，取消原因不能为空！', $order_id)]);exit; }
        
        $orderModel = Orders::find()->where(['id' => $order_id])->one();
        $status_arr1 = [4,8];
        $status_arr2 = [13];

        $tr = Yii::$app->db->beginTransaction();
        if (in_array($orderModel->status, $status_arr1)) {
            try {
                // 已发货的订单，有可能是转寄仓的订单若是转寄仓已发货，不能取消了
                if ($orderModel->status == 4) {
                    $isForward = Forward::find()->where(['new_id_order' => $order_id, 'status' => 2])->one();
                    if (!empty($isForward)) {
                        return json_encode(['status' => 0, 'msg' => sprintf('订单ID:%s,取消失败！转寄仓已发货不能取消！', $order_id)]);exit;
                    }
                }
                $orderItems = OrdersItem::find()->where(['order_id' => $orderModel->id])->all();
                foreach ($orderItems as $k => $item) {
                    $locationStock = LocationStock::find()->where(['sku' => $item->sku])->one();
                    $original_qty = $locationStock->stock;
                    if (!$locationStock) {
                        return json_encode(['status' => 0, 'msg' => sprintf('订单ID:%s,取消失败！该订单的sku:%s,没有存在库位库存！', $order_id, $item->sku)]);exit;
                    }
                    $locationStock->stock+= $item->qty;
                    $locationStock->update_date = date("Y-m-d H:i:s", time());

                    // stock记录
                    $insertDataLogs = [
                        'order_id' => $orderModel->id, 
                        'sku' => $item->sku, 
                        'qty' => $item->qty, 
                        'stock_code' => $locationStock->stock_code, 
                        'location_code' => $locationStock->location_code, 
                        'uid' => Yii::$app->user->id, 
                        'create_date' => date('Y-m-d H:i:s', time()), 
                        'type' => 4, 
                        'location_stock_id' => $locationStock->id,
                        'original_qty' => $original_qty
                    ];
                    Yii::$app->db->createCommand()->insert(LocationLog::tableName(), $insertDataLogs)->execute();
                    $locationStock->save();
                }

                $orderModel->status = 10;
                $orderModel->comment_u = $notes;

                if ($orderModel->save() && OrderRecord::addRecord($order_id,$orderModel->status,4,'取消订单,退返库存')) {
                    $tr->commit();
                    return json_encode(['status' => 1, 'msg' => sprintf('订单ID:%s,取消成功！请去确认！', $order_id)]);exit;
                } else {
                    $tr->rollback();
                    return json_encode(['status' => 0, 'msg' => sprintf('订单ID:%s,取消失败，请稍候重试！', $order_id)]);exit;
                }
            } catch (Exception $e) {
                $tr->rollback();
                return json_encode(['status' => 0, 'msg' => sprintf('订单ID:%s,取消失败，请稍候重试！', $order_id)]);exit;
            }
            
        } else if(in_array($orderModel->status, $status_arr2)) {
            try {
                // 转寄仓的取消，解除转寄关系
                //获取订单信息
                $order_info = Orders::find()->where(array('id'=>$order_id))->one();
                if (empty($order_info))
                {   
                    return json_encode(['status' => 0, 'msg' => sprintf('订单ID:%s,订单信息为空！', $order_id)]);exit;
                }
                if (!in_array($order_info['status'],array(13)))
                {
                    return json_encode(['status' => 0, 'msg' => sprintf('订单ID:%s,订单状态不为待转运状态！', $order_id)]);exit;
                }
                //获取转运仓对应的订单信息
                $forward_info = Forward::find()->where(array('new_id_order'=> $order_id))->one();
                if (!$forward_info)
                {
                    return json_encode(['status' => 0, 'msg' => sprintf('订单ID:%s,转寄仓未找到对应订单，请咨询技术！', $order_id)]);exit;
                }
                
                //修改已匹配转寄仓订单记录，改为未匹配
                $forward_id_order = $forward_info['id_order'];
                $forward_info->new_id_order = 0;
                $forward_info->status = 0;
                $forward_info->new_lc_number = '';
                $forward_info->forward_time = '';
                $res = $forward_info->save();

                $order_info->status = 10;   //已取消
                $order_info->comment_u = $notes;
                $order_info->update_time = date('Y-m-d H:i:s', time());
                $res_two = $order_info->save();

                $res_three = OrderRecord::addRecord($order_id,10,4,'取消订单，解除待转运');

                $res_four = Yii::$app->db->createCommand("update orders set status = 14 where id = ".$forward_id_order)->execute();
                $res_five = OrderRecord::addRecord($forward_id_order,14,4,'取消订单，转寄仓关系解除，订单状态更新为转寄仓未匹配');

                if (!$res || !$res_two || !$res_three || !$res_four || !$res_five)
                {
                    $tr->rollBack();
                    return json_encode(['status' => 0, 'msg' => sprintf('订单ID:%s,取消失败,请稍候重试！', $order_id)]);exit;
                }
                $tr->commit();
                return json_encode(['status' => 1, 'msg' => '取消成功！请去确认！']);
            } catch (Exception $e) {
                $tr->rollback();
                return json_encode(['status' => 0, 'msg' => sprintf('订单ID:%s,取消失败,请稍候重试！', $order_id)]);exit;
            }
        }

    }
}