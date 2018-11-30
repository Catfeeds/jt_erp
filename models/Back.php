<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "back".
 *
 * @property int $id
 * @property string $back 退库单号
 * @property string $order_number 采购单号
 * @property string $consignee 退库收货人
 * @property string $phone 退库收货人电话，可以是手机也可以是座机
 * @property string $address 退库收货人地址
 * @property string $express 快递单号
 * @property int $type 退库类型，1采购退库，2发货退库
 * @property int $status 退库单状态
 * @property string $notes 备注
 * @property string $create_time 创建时间
 * @property int $create_uid 创建人id
 * @property string $serial_number 退款流水号
 */
class Back extends \yii\db\ActiveRecord
{
    // 退库状态
    public $status_arr = [
        '0' => '草稿',
        '1' => '待库房接收',
        '2' => '已接收待填物流号',
        '3' => '已关闭'
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'back';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status', 'create_uid'], 'integer'],
            [['create_time'], 'safe'],
            [['back', 'order_number', 'consignee', 'phone', 'express', 'serial_number'], 'string', 'max' => 50],
            [['address', 'notes'], 'string', 'max' => 255],
            [['amount', 'amount_real', 'expressPrice'], 'double']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'back' => '退库单号',
            'order_number' => '采购单号',
            'consignee' => '收货人',
            'phone' => '收货人电话',
            'address' => '收货地址',
            'express' => '物流单号',
            'type' => '退货类型',
            'status' => '退库状态',
            'notes' => '备注',
            'create_time' => '退库时间',
            'create_uid' => '操作人',
            'serial_number' => '退款流水号',
            'amount' => '采购成本金额',
            'amount_real' => '最终退款金额',
            'expressPrice' => '退货运费'
        ];
    }
}
