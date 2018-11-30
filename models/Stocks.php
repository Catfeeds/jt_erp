<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "stocks".
 *
 * @property int $id
 * @property string $stock_code 仓库代码
 * @property string $sku sku
 * @property int $stock 库存
 * @property string $cost 成本
 * @property int $uid 操作人
 * @property string $create_date 创建时间
 * @property string $update_date 创建时间
 */
class Stocks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stocks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stock_code', 'sku', 'uid'], 'required'],
            [['stock', 'uid'], 'integer'],
            [['cost'], 'number'],
            [['create_date', 'update_date'], 'safe'],
            [['stock_code'], 'string', 'max' => 5],
            [['sku'], 'string', 'max' => 13],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stock_code' => '仓库代码',
            'sku' => 'sku',
            'stock' => '库存',
            'cost' => '成本',
            'uid' => '操作人',
            'create_date' => '创建时间',
            'update_date' => '更新时间',
        ];
    }


    /**
     * 查询库存是否可用
     * @param $sku
     * @param $num
     * @return boolean
     */
    public function checkStock($sku, $num)
    {
        $sql = "SELECT SUM(stock) AS stock FROM location_stock WHERE sku=:sku AND stock_code!='退货仓'";
        $stock = Yii::$app->db->createCommand($sql)->bindValue(':sku', $sku)->queryOne();

        //$sql2 = "SELECT SUM(A.qty) AS num FROM orders_item AS A LEFT JOIN orders AS B ON A.order_id=B.id WHERE A.sku=:sku AND B.status IN ('待发货', '捡货中')";
        //PDA 拣货已减库存了
        $sql2 = "SELECT SUM(A.qty) AS num FROM orders_item AS A LEFT JOIN orders AS B ON A.order_id=B.id WHERE A.sku=:sku AND B.status in (7,12)";  //待发货，超时单
        $qty = Yii::$app->db->createCommand($sql2)->bindValue(':sku', $sku)->queryOne();

        if ($stock['stock'] >= ($qty['num']+$num)) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * 查询库存是否可用
     * @param $sku
     * @param $num
     * @return boolean
     */
    public function check_stock($sku, $num)
    {
        $sql = "SELECT SUM(stock) AS stock FROM location_stock WHERE sku='".$sku."'";
        $stock = Yii::$app->db->createCommand($sql)->queryOne();

        $sql2 = "SELECT SUM(A.qty) AS num FROM orders_item AS A LEFT JOIN orders AS B ON A.order_id=B.id WHERE A.sku='".$sku."' AND B.status in (7,12)";  //待发货，超时单
        $qty = Yii::$app->db->createCommand($sql2)->queryOne();

        $stock_num = isset($stock['stock']) && $stock['stock'] ? $stock['stock']:0;
        $qty_num = isset($qty['num']) && $qty['num'] ? $qty['num'] : 0;
        if ($stock_num >= ($qty_num+$num))
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    /**
     * 查询库存是否可用
     * @param $sku
     * @param $num
     * @return bool|mixed
     */
    public static function checkStockAbnormal($sku, $num)
    {
        //获取变体库存
        $sku_arr_qty = array();
        if ($sku == 'gift')
        {
            $sku_arr = Forward::$gift_arr;
        }
        else
        {
            $sku_arr = Forward::sku_change_back($sku);
        }

        foreach ($sku_arr as $sku_change)
        {
            $stock_num = self::get_stock($sku_change,true);
            $lock_num = self::get_lock_stock($sku_change,true);
            if ($stock_num >= ($lock_num+$num) )
            {
                $sku_arr_qty[$sku_change] = $num;
            }
        }
        return $sku_arr_qty?$sku_arr_qty:false;
    }

    /**
     * SKU可用库存
     * 可用库存=实际库存-待发货
     * @param $sku
     * @return int
     * @throws \yii\db\Exception
     */
    public function inventoryBySku($sku)
    {
        $sql = "SELECT SUM(stock) AS stock FROM location_stock WHERE sku=:sku AND stock_code!='退货仓'";
        $stock = Yii::$app->db->createCommand($sql)->bindValue(':sku', $sku)->queryOne();

        $sql = "SELECT SUM(A.qty) AS num FROM orders_item AS A LEFT JOIN orders AS B ON A.order_id=B.id WHERE A.sku=:sku AND B.status in (7,12)";   //超时单和待发货订单
        $qty = Yii::$app->db->createCommand($sql)->bindValue(':sku', $sku)->queryOne();

        return $stock['stock'] - $qty['num'];
    }

    /**
     * 锁定库存
     * @param $sku
     */
    public function lockStock($sku)
    {
        $sql = "SELECT SUM(A.qty) AS num FROM orders_item AS A LEFT JOIN orders AS B ON A.order_id=B.id WHERE A.sku=:sku AND B.status in (7,12)";   //库存锁定加上超时单
        $qty = Yii::$app->db->createCommand($sql)->bindValue(':sku', $sku)->queryOne();
        return $qty['num'];
    }

    /**
     * 采购与待采购订单的SKU数量
     * @param $sku
     * @return mixed
     * @throws \yii\db\Exception
     */
    static public function countPurchaseTotalBySku($sku)
    {
        $sql = "SELECT SUM(A.qty) AS num FROM orders_item AS A LEFT JOIN orders AS B ON A.order_id=B.id WHERE A.sku=:sku AND B.status IN (3, 20, 21)";
        $qty = Yii::$app->db->createCommand($sql)->bindValue(':sku', $sku)->queryOne();
        return $qty['num'];
    }

    /**
     * 在途库存
     * @param $sku
     * @return int
     * 已入库跟退款的不计算在途库存
     */
    public function transitInventoryBySku($sku)
    {
        $sql = "SELECT SUM(qty-delivery_qty-refound_qty) qty from purchase_items A LEFT JOIN  purchases B ON A.purchase_number=B.order_number WHERE sku=:sku AND status!=4 AND status!=3";
        $qty = Yii::$app->db->createCommand($sql)->bindValue(':sku', $sku)->queryOne();
        if($qty['qty']>0)
        {
            return $qty['qty'];
        }else{
            return 0;
        }

    }

    /**
     * 中间表未采购
     * @param $sku
     */
    public function repTransBySku($sku)
    {
        $sql2 = "SELECT SUM(supplement_number) AS stocks FROM replenishment WHERE sku_id=:sku AND status='未采购'";
        $qty2 = Yii::$app->db->createCommand($sql2)->bindValue(':sku', $sku)->queryOne();
        return $qty2['stocks'];
    }

    // sku信息
    // jieson 2018.10.08
    public function skuInfo($sku, $type)
    {
        $sql = '';
        switch($type)
        {
            case "name":
                $sql = "select title as res from products_base where spu='{$sku}'";
                break;
            case "model":
                $sql = "select concat(color, '/', size) as res from products_variant where sku='{$sku}'";
                break;
        }
        $res = Yii::$app->db->createCommand($sql)->queryOne();
        return $res['res'];
    }


    /**
     * 获取非正常匹配库存(对应sku变体库存)
     * @param $sku
     * @return mixed
     */
    public static function get_stock($sku,$flag= false)
    {
        if (in_array($sku,Forward::$gift_arr) && $flag)
        {
            $sql = "SELECT SUM(b.stock) AS stock FROM location_stock  b  WHERE sku like '".$sku."%' AND b.stock_code!='退货仓'";
        }
        else
        {
            $sql = "SELECT SUM(b.stock) AS stock FROM location_stock  b  WHERE sku = '".$sku."' AND b.stock_code!='退货仓'";
        }
        $stock = Yii::$app->db->createCommand($sql)->bindValue(':sku', $sku)->queryOne();

        return isset($stock['stock']) && $stock['stock'] ? $stock['stock'] : 0;
    }

    /**
     * 获取变体sku的锁定库存
     * @param $sku
     * @return mixed
     */
    public static function get_lock_stock($sku,$flag=false)
    {
        if (in_array($sku,Forward::$gift_arr) && $flag)
        {
            $sql = "SELECT SUM(b.qty) AS num FROM orders_item AS b LEFT JOIN orders AS a ON b.order_id=a.id WHERE sku like '".$sku."%' AND a.status in (7,12)";  //待发货，超时单
        }
        else
        {
            $sql = "SELECT SUM(b.qty) AS num FROM orders_item AS b LEFT JOIN orders AS a ON b.order_id=a.id WHERE sku='".$sku."' AND a.status in (7,12)";  //待发货，超时单
        }
        $qty = Yii::$app->db->createCommand($sql)->bindValue(':sku', $sku)->queryOne();

        return isset($qty['num']) && $qty['num'] >0  ? $qty['num'] : 0;
    }

    /**
     * 验证订单库存
     * @param $id_order
     */
    public static function check_stock_abnormal($id_order)
    {
        //根据订单号获取订单详情信息
        $order_item = Forward::order_integration_abnormal_new($id_order);
        $stock = true;
        $sku_num_arr = array();
        foreach ($order_item as $sku => $qty)
        {
            if ($stock)
            {
                $stock = static::checkStockAbnormal($sku, $qty);
                if ($stock)
                {
                    $sku_num_arr[$sku] = $stock;
                }
            }
            else
            {
                break;
            }
        }

        return $stock?$sku_num_arr:false;

    }

    /**
     * 获取相应sku的变体有效库存
     */
    public static function get_stock_abnormal_sku($sku,$num)
    {
        $stockModel = new Stocks();
        //获取变体sku及相应库存
        $sku_arr = Forward::sku_change($sku,false,false,true);
        $change_sku_qty = array();
        foreach ($sku_arr as $sku_change)
        {
            $valid_qty = $stockModel->inventoryBySku($sku_change);
            if ($valid_qty >= $num)
            {
                $change_sku_qty[$sku_change]['qty'] = $valid_qty;
            }
        }

        return $change_sku_qty;
    }

    public static function get_gift_sku_qty($num)
    {
        $stockModel = new Stocks();
        //获取变体sku及相应库存
        $sku_arr = Forward::$gift_arr;
        $change_sku_qty = array();
        foreach ($sku_arr as $sku_change)
        {
            $valid_qty = $stockModel->inventoryBySku($sku_change);
            if ($valid_qty >= $num)
            {
                $change_sku_qty[$sku_change]['qty'] = $valid_qty;
            }
        }

        return $change_sku_qty;
    }

    /**
     * 匹配变体库存组合
     * @param $id_order
     * @return array|bool
     */
    public static function sku_stock_match($id_order)
    {
        $stock_sku_arr = self::check_stock_abnormal($id_order);
        //查找订单赠品sku
        $sku_gift_arr = self::sku_gift($id_order);
        $sku_gift_stock = $sku_stock = array();
        $sku_gift_stock_two = $sku_stock_two = array();

        if ($stock_sku_arr)
        {
            foreach ($stock_sku_arr as $sku => $value)
            {
                foreach ($value as $s => $qty)
                {
                    //变体数据获取
                    if ($sku_gift_arr)
                    {
                        if (in_array($s,$sku_gift_arr))
                        {
                            $sku_gift_stock[$sku][$s] = $qty;
                        }
                        else
                        {
                            $sku_gift_stock_two[$sku][$s] = $qty;
                        }
                    }

                    //同色，同款优先
                    if (substr($s,0,10) == substr($sku,0,10))
                    {
                        $sku_stock[$sku][$s] = $qty;
                    }
                    else
                    {
                        $sku_stock_two[$sku][$s] = $qty;
                    }
                }
            }

            $sku_gift_stock_new = $sku_gift_stock ? $sku_gift_stock : $sku_gift_stock_two;
            $sku_stock_new = $sku_stock ? $sku_stock : $sku_stock_two;

            return array_merge($sku_gift_stock_new,$sku_stock_new);
        }
        else
        {
            return false;
        }
    }

    //查找订单赠品sku
    public static function sku_gift($id_order)
    {
        $sku_arr = array();
        $sku_str = implode("','",Forward::$gift_arr);
        $sql = "select sku from orders_item where substring(sku,1,8) in ('".$sku_str."') and order_id = ".$id_order;
        $sku = Yii::$app->db->createCommand($sql)->queryAll();
        if ($sku)
        {
            foreach ($sku as $v)
            {
                $sku_arr[] = substr($v['sku'],0,8);
            }
        }
        return $sku_arr;
    }

    //获取产品组合数
    public static function get_stock_rand($data_arr)
    {
        $return = array();
        foreach ($data_arr as $sku => $data)
        {
            $return[] = $data;
        }
        return $return;
    }

    /**
     * 根据详情获取数据
     * @param $id_order
     * @param $info
     */
    public static function change_order_item_stock($id_order,$info)
    {
        $price = 0;
        $order_total = Yii::$app->db->createCommand("select total,country,comment_u,order_no from orders where id = ".$id_order)->queryOne();
        $order_no = $order_total['order_no'];
        $comment_u = $order_total['comment_u'];
        $comment_u_str = '非正常匹配:';
        $comment_u_str .= Forward::get_order_sku($id_order);
        $comment_u_str .= '->';
        $sku_arr_str = array();
        $tr = Yii::$app->db->beginTransaction();
        $res = Yii::$app->db->createCommand("delete from orders_item where order_id = ".$id_order)->execute();
        if (!$res)
        {
            $tr->rollBack();
            return false;
        }
        $flag = true;
        foreach ($info as $k => $v)
        {
            if ($k == 0)
            {
                continue;
            }
            $val = explode('&',$v);
            $sku = isset($val[0]) && $val[0] ? $val[0]:'';
            $qty = isset($val[1]) && $val[1] ? $val[1]:0;
            if (!$sku || !$qty)
            {
                $tr->rollBack();
                return false;
            }

            if (in_array($sku,Forward::$gift_arr))
            {
                $product = OrdersItem::getProductBySpu($sku);
            }
            else
            {
                $product = OrdersItem::getProductBySku($sku);
                if ($flag)
                {
                    $price = $order_total['total']/$qty;
                    $flag = false;
                }

            }

            if (!$product)
            {
                $tr->rollBack();
                return false;
            }

            //获取sku信息
            $sku_arr_str[] = $product->sku;
            $sql = "insert into orders_item VALUES (null,".$id_order.",'".$product->sku."',".$qty.",".$price.",'".$product->color."','".$product->size."','".$product->image."');";
            $flag = Yii::$app->db->createCommand($sql)->query();
            if (!$flag)
            {
                $tr->rollBack();
                return false;
            }
        }

        //对库存进行验证
        $order_item = OrdersItem::get_order_item($id_order);
        $stock_model = new Stocks();
        foreach ($order_item as $item)
        {
            $stock = $stock_model->check_stock($item['sku'], $item['qty']);
            if (!$stock)
            {
                $tr->rollBack();
                return false;
            }
        }
        $comment_u_str .= implode(',',$sku_arr_str);
        $comment_u = $comment_u_str.';'.$comment_u;
        if ($order_total['country'] == 'ID')
        {
            $res_one = Yii::$app->db->createCommand("update orders set status = 7,order_no = '".$order_no."B' ,comment_u = '".$comment_u."' where id = ".$id_order)->execute();
        }
        else
        {
            $res_one = Yii::$app->db->createCommand("update orders set status = 7 ,comment_u = '".$comment_u."' where id = ".$id_order)->execute();
        }
        if (!$res_one || !OrderRecord::addRecord($id_order,7,4,'待操作订单匹配库存'))
        {
            $tr->rollBack();
            return false;
        }
        $tr->commit();
        return true;
    }

    /**
     * @param $id_order
     * @return bool
     */
    public static function change_order_operated($id_order)
    {
        $tr = Yii::$app->db->beginTransaction();
        $order_model = Orders::findOne($id_order);
        $order_model->status = 16;  //订单状态变更为待操作
        if (!$order_model->save() || !OrderRecord::addRecord($id_order,16,4,'执行脚本非正常匹配，订单状态变更为待操作',1))
        {
            $tr->rollBack();
            return false;
        }
        $tr->commit();
        return true;
    }

    /**
     * 订单号匹配库存
     * @param $id_order
     * @return bool
     */
    public static function check_stock_by_id($id_order)
    {
        $flag = true;
        $forward_model = new Forward();
        $order_item = $forward_model->order_integration($id_order);
        if ($order_item)
        {
            foreach ($order_item as $sku => $qty)
            {
                if ($flag)
                {
                    $flag = Stocks::check_stock($sku,$qty['qty']);
                }
                else
                {
                    return false;
                }
            }

            //匹配到库存进行订单处理
            if ($flag)
            {
                $tr = Yii::$app->db->beginTransaction();
                $order_model = Orders::findOne($id_order);
                $order_model->status = 7;  //订单状态变更为待操作
                if (!$order_model->save() || !OrderRecord::addRecord($id_order,7,4,'执行脚本匹配库存，更新待发货',1))
                {
                    $tr->rollBack();
                    return false;
                }
                $tr->commit();
                return true;
            }
        }
        return false;
    }

}
