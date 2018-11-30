<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "shipping_order_settlement".
 *
 * @property int $id
 * @property int $id_order 订单号
 * @property string $lc_number 运单号
 * @property int $back_order_total 回款总金额
 * @property int $back_order 回款金额
 * @property string $currency  货币
 * @property int $status '结款状态(0未结款,1部分结款,2已结款)'
 * @property string created_at 结款日期
 * @property string update_at 更新日期
 */
class ShippingOrderSettlement extends \yii\db\ActiveRecord
{

    public static $status_arr = [
        '0' => '未结款',
        '1' => '部分结款',
        '2' => '已结款',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shipping_order_settlement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status','id_order'], 'integer'],
            [['lc_number','currency'], 'string', 'max' => 255],
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
            'lc_number' => '运单号',
            'back_order_total' => '回款总金额',
            'back_order' => '回款金额',
            'currency' => '货币',
            'status' => '状态',
            'created_at' => '创建时间',
            'update_at' => '更新时间',
        ];
    }

    /**
     * 物流结算单确认后进行订单物流结算统计
     * 1. 结算金额，正数 - 已结算
       2. 结算金额为负数， 并且，前置状态非已结算 - 部分结算
       3. 结算金额为负数，且物流状态为拒收或退款 - 已结算
       4. 结算金额为负数并且，前置状态已结算 - 已结算
       5. 结算金额为负数，创建时间大于1个月 - 已结算
     * @param $id
     * @return bool
     */
    public static function add_order_settlement($id)
    {
        //获取确认单详情信息
        $ShippingSettlementItem = new ShippingSettlementItem();
        $shipping_settlement_item_arr = $ShippingSettlementItem->find()->where(['id_shipping_settlement' => $id])->asArray()->all();
        $tr = ActiveRecord::getDb()->beginTransaction();
        foreach ($shipping_settlement_item_arr as $item)
        {
            $order_settlement_model = new ShippingOrderSettlement();
            $order_state_model = new ShippingLogisticsState();
            $order_settlement = $order_settlement_model->find()->where(array('id_order'=> $item['id_order']))->one();
            $order_state = $order_state_model->find()->where(array('id_order'=> $item['id_order']))->one();
            if ($order_settlement)
            {
                $back_order_total = $order_settlement->back_order_total+$item['back_order_total']+$item['cod_fee']+$item['shipping_fee']+$item['other_fee'];
                $back_order = $order_settlement->back_order_total+$item['back_order_total'];
                $status = 1;
                if ($back_order_total > 0)
                {
                    $status = 2;
                }
                if ($back_order_total < 0 && $order_settlement->status != 2)
                {
                    $status = 1;
                }
                if ($back_order_total < 0 && $order_state->state == 3)
                {
                    $status = 1;
                }
                if ($back_order_total < 0 && $order_settlement->status == 2)
                {
                    $status = 2;
                }
                $res = Yii::$app->getDb()->createCommand("update shipping_order_settlement set back_order_total = ".$back_order_total.",status = ".$status.",back_order = ".$back_order." where id_order = ".$item['id_order'])->execute();
                if (!$res)
                {
                    $tr->rollBack();
                    return false;
                }
            }
            else
            {
                $back_order = $item['back_order_total'];
                $back_order_total = $item['back_order_total']+$item['cod_fee']+$item['shipping_fee']+$item['other_fee'];
                $status = $back_order_total > 0 ? 2:1;   //创建时，回款金额大于0则为已回款
                $order_settlement_model->id_order = $item['id_order'];
                $order_settlement_model->lc_number = $item['lc_number'];
                $order_settlement_model->status = $status;
                $order_settlement_model->back_order_total = $back_order_total;
                $order_settlement_model->back_order = $back_order;
                $order_settlement_model->currency = $item['currency'];
                if (!$order_settlement_model->save())
                {
                    $tr->rollBack();
                    return false;
                }
            }
        }
        $tr->commit();
        return true;
    }

}
