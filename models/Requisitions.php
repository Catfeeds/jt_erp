<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requisitions".
 *
 * @property int $id
 * @property string $order_number 单号
 * @property string $order_type 调拨类型: 库内调拨、库间调拨、退货调拨
 * @property string $out_stock 调出仓
 * @property string $in_stock 调入仓，退货类型的调入仓为：退货仓
 * @property string $create_date 时间
 * @property int $create_uid 操作人
 * @property int $order_status 状态：0草稿，1已确认，2已完成
 */
class Requisitions extends \yii\db\ActiveRecord
{
    public $status_array = [
        0 => '草稿',
        1 => '已确认',
        2 => '已完成',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'requisitions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_number', 'order_type'], 'required'],
            [['create_date'], 'safe'],
            [['create_uid', 'order_status'], 'integer'],
            [['order_number', 'order_type', 'out_stock', 'in_stock'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_number' => '调拨单号',
            'order_type' => '调拨类型',
            'out_stock' => '调出仓',
            'in_stock' => '调入仓',
            'create_date' => '时间',
            'create_uid' => '操作人',
            'order_status' => '状态',
        ];
    }
}
