<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shipping_settlement_item".
 *
 * @property int $id
 * @property int $id_shipping_settlement 回款ID
 * @property int $id_order 订单ID
 * @property string $lc_number  运单号
 * @property int $back_order_total 回款金额
 * @property int $cod_fee COD手续费
 * @property int $shipping_fee 实际运费
 * @property int $other_fee 其它费用
 * @property string $currency 货币
 * @property int $uid 操作人
 * @property string created_at 创建日期
 */
class ShippingSettlementItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shipping_settlement_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_order','id_shipping_settlement','uid'], 'integer'],
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
            'id_shipping_settlement' => '回款ID',
            'id_order' => '订单号',
            'lc_number' => '运单号',
            'back_order_total' => '回款金额',
            'cod_fee' => 'COD金额',
            'shipping_fee' => '实际运费',
            'other_fee' => '其他费用',
            'currency' => '货币',
            'uid' => '操作人',
            'created_at' => '创建时间',
        ];
    }
}
