<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shipping_settlement".
 *
 * @property int $id
 * @property string $settlement_number 回款单编码
 * @property string $lc 货代
 * @property int $back_total 回款金额
 * @property int $other_fee 其他费用
 * @property string $currency  货币
 * @property int $status '状态 1草稿，2已确认'
 * @property int $uid  操作人
 * @property string $date_time  回款日期
 * @property string $created_at 创建时间
 * @property string $update_at 修改时间
 */
class ShippingSettlement extends \yii\db\ActiveRecord
{

    public static $status_arr = [
        '1' => '草稿',
        '2' => '确认',
        '3' => '作废',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shipping_settlement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status','uid'], 'integer'],
            [['lc','currency','settlement_number','date_time'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'settlement_number' => '回款编码',
            'lc' => '物流商',
            'back_total' => '回款金额',
            'other_fee' => '其他费用',
            'currency' => '货币',
            'status' => '状态',
            'uid' => '操作人',
            'date_time' => '回款日期',
            'created_at' => '创建时间',
            'update_at' => '更新时间',
        ];
    }
}
