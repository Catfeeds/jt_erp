<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "receipt_logs_items".
 *
 * @property int $id
 * @property int $receipt_id 上架单号
 * @property string $order_number 采购单号
 * @property string $sku
 * @property int $buy_qty 应收数量
 * @property int $get_qty 实收数量
 * @property string $location_code 库位编号
 * @property int $warning_status 异常状态 0正常 1待处理 2已确认
 * @property string $update_date
 * @property int $update_uid 异常处理人
 */
class ReceiptLogsItems extends \yii\db\ActiveRecord
{

    public $status_array = [0=>'正常', 1=>'待处理', 2=>'已确认', 3=>'已上架'];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'receipt_logs_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['receipt_id', 'order_number', 'sku', 'buy_qty', 'get_qty', 'location_code'], 'required'],
            [['receipt_id', 'buy_qty', 'get_qty', 'warning_status', 'update_uid'], 'integer'],
            [['update_date'], 'safe'],
            [['order_number', 'location_code'], 'string', 'max' => 50],
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
            'receipt_id' => 'Receipt ID',
            'order_number' => 'Order Number',
            'sku' => 'Sku',
            'buy_qty' => 'Buy Qty',
            'get_qty' => 'Get Qty',
            'location_code' => 'Location Code',
            'warning_status' => 'Warning Status',
            'update_date' => 'Update Date',
            'update_uid' => 'Update Uid',
        ];
    }
}
