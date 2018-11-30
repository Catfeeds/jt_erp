<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int $id_order
 * @property string $create_at
 * @property string $cancel_at
 * @property string $confirm_at
 * @property string $purchasing_at
 * @property string $purchase_at
 * @property string $sending_at
 * @property string $send_at
 * @property string $receive_at
 */
class OrderStatusChange extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_status_change';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_order', 'create_at'], 'required'],
            [['create_at', 'confirm_at', 'cancel_at', 'purchasing_at', 'purchase_at', 'sending_at', 'send_at', 'receive_at'], 'string', 'max' => 255],
            [['id_order', 'create_at', 'confirm_at', 'cancel_at', 'purchasing_at', 'purchase_at', 'sending_at', 'send_at', 'receive_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '序号',
            'id_order' => '订单ID',
            'create_at' => '创建时间',
            'cancel_at' => '订单取消耗时',
            'confirm_at' => '订单确认耗时',
            'purchasing_at' => '待采购耗时',
            'purchase_at' => '采购耗时',
            'sending_at' => '待发货耗时',
            'send_at' => '发货耗时',
            'receive_at' => '签收耗时',
        ];
    }


}
