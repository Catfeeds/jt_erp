<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shipping_logistics_state".
 *
 * @property int $id
 * @property int $id_order 订单号
 * @property string $country 国家
 * @property string $lc_number  运单号
 * @property string $lc 货代
 * @property int $state 物流状态(1在途,2已签收,3拒签,4丢件)
 * @property int $type 订单类型(1有效,2无效,3转寄仓)
 * @property string created_at 创建日期
 * @property string update_at 更新日期
 */
class ShippingLogisticsState extends \yii\db\ActiveRecord
{

    public static $state_arr = [
        '1' => '在途',
        '2' => '已签收',
        '3' => '拒收',
        '4' => '丢件',
    ];

    public static $type_arr = [
        '1' => '有效',
        '2' => '无效',
        '3' => '转寄',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shipping_logistics_state';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['state','type'], 'integer'],
            [['country'], 'required'],
            [['lc_number', 'lc'], 'string', 'max' => 255],
            [['country'], 'string', 'max' => 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_order' => '订单号',
            'country' => '国家',
            'lc_number' => '运单号',
            'lc' => '物流商',
            'state' => '物流状态',
            'type' => '订单类型',
            'created_at' => '创建时间',
            'update_at' => '更新时间',
        ];
    }

    public static function save_shipping_state($id_order,$lc_number,$state)
    {
        $ShippingLogisticsState = new ShippingLogisticsState();
        $is_forward = self::check_forward($id_order);
        $order_info = Yii::$app->getDb()->createCommand("select lc_number,country,lc,status from orders where id = ".$id_order)->queryOne();
        $type = $type = $order_info['status'] == 10 ? 2 : ($is_forward ? 3:1);
        $state_info = ShippingLogisticsState::findOne(array('id_order' => $id_order));
        if ($id_order && $lc_number)
        {
            //订单号运单号进行验证
            if (isset($order_info['lc_number']) && $order_info['lc_number'] == $lc_number)
            {
                if ($state_info)    //更新记录
                {
                    $res = Yii::$app->getDb()->createCommand("update shipping_logistics_state set state = ".$state." where id_order = ".$id_order)->execute();
                }
                else    //插入新记录
                {
                    $ShippingLogisticsState->id_order = $id_order;
                    $ShippingLogisticsState->country = $order_info['country'];
                    $ShippingLogisticsState->lc = $order_info['country'];
                    $ShippingLogisticsState->lc_number = $lc_number;
                    $ShippingLogisticsState->state = $state;
                    $ShippingLogisticsState->type = $type;
                    $res = $ShippingLogisticsState->save();
                }
                $return_info = $res ? array('status' => 1,'msg' => '操作成功'):array('status' =>0,'data'=> $id_order,'msg' => '更新失败');
            }
            elseif (!isset($order_info['lc_number']))    //如果运单号没有维护则进行运单号维护
            {
                $res_one = Yii::$app->getDb()->createCommand("update orders set lc_number = '".$lc_number."' where id = ".$id_order)->execute();
                if ($state_info)    //更新记录
                {
                    $res = Yii::$app->getDb()->createCommand("update shipping_logistics_state set state = ".$state." where id_order = ".$id_order)->execute();
                }
                else    //插入新记录
                {
                    $ShippingLogisticsState->id_order = $id_order;
                    $ShippingLogisticsState->country = $order_info['country'];
                    $ShippingLogisticsState->lc = $order_info['country'];
                    $ShippingLogisticsState->lc_number = $lc_number;
                    $ShippingLogisticsState->state = $state;
                    $ShippingLogisticsState->type = $type;
                    $res = $ShippingLogisticsState->save();
                }
                $return_info = $res && $res_one ? array('status' => 1,'msg' => '操作成功'):array('status' =>0,'data'=> $id_order,'msg' => '更新失败');
            }
            else
            {
                $return_info = array('status'=> 0 ,'data' => $id_order,'msg' => '订单号对应运单号不符,请核对后再操作');
            }
        }
        else
        {
            $return_info = array('status'=> 0 ,'data' => $id_order,'msg' => '没有获取到相应的订单号和运单号');
        }
        return $return_info;
    }

    //验证是否是转寄仓订单
    public static function check_forward($id_order)
    {
        $res  = Yii::$app->getDb()->createCommand("select id from forward where new_id_order = ".$id_order)->queryOne();
        return $res?true:false;
    }

    public static function add_shipping_state($id_order,$lc_number,$lc='')
    {
        $state_info = ShippingLogisticsState::findOne(array('id_order' => $id_order));
        $ShippingLogisticsState = new ShippingLogisticsState();
        $order_info = Yii::$app->getDb()->createCommand("select lc_number,country,lc,status from orders where id = ".$id_order)->queryOne();
        $is_forward = self::check_forward($id_order);
        $type = $type = $order_info['status'] == 10 ? 2 : ($is_forward ? 3:1);
        if (!$state_info && $order_info)
        {
            $ShippingLogisticsState->id_order = $id_order;
            $ShippingLogisticsState->country = $order_info['country'];
            $ShippingLogisticsState->lc = $lc;
            $ShippingLogisticsState->lc_number = $lc_number;
            $ShippingLogisticsState->type = $type;
            $res = $ShippingLogisticsState->save();
        }

        if ($state_info && $order_info)
        {
            if ($lc_number == $state_info->lc_number)
            {
                $res = Yii::$app->getDb()->createCommand("update shipping_logistics_state set lc_number = '".$lc_number."',lc = '".$lc."',state = 1 where id_order = ".$id_order)->execute();
            }
            else
            {
                $res = Yii::$app->getDb()->createCommand("update shipping_logistics_state set lc_number = '".$lc_number."',lc = '".$lc."' where id_order = ".$id_order)->execute();

            }
        }
        $flag = $res ? true : false;
        return $flag;
    }

}
