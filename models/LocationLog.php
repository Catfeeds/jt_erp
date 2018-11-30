<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "location_log".
 *
 * @property int $id
 * @property string $order_id 订单ID,上架的为空，发货的对应订单ID
 * @property string $sku sku
 * @property int $qty 数量，上架为正，下架为负
 * @property string $stock_code 仓库CODE
 * @property string $location_code 库位CODE
 * @property int $uid 操作人
 * @property string $create_date 创建时间
 */
class LocationLog extends \yii\db\ActiveRecord
{
    public $status_array = array(
        0 => '采购入库',
        1 => '订单出库',
        2 => '调拨出库',
        3 => '采购退货出库',
        4 => '订单取消入库',
        5 => '盘点入库'
    );
    public $start_date;
    public $end_date;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'location_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sku'], 'required'],
            [['qty', 'uid'], 'integer'],
            [['create_date'], 'safe'],
            [['order_id', 'location_code'], 'string', 'max' => 50],
            [['sku'], 'string', 'max' => 13],
            [['stock_code'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'sku' => 'Sku',
            'qty' => 'Qty',
            'stock_code' => 'Stock Code',
            'location_code' => 'Location Code',
            'uid' => 'Uid',
            'create_date' => 'Create Date',

        ];
    }
}
