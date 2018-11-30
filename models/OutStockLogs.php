<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stock_logs".
 *
 * @property int $id
 * @property string $sku
 * @property string $order_id 订单号
 * @property int $qty 数量
 * @property double $cost 成本
 * @property int $uid 操作人
 * @property int $log_type 类别：1采购入库，2销售出库，3调拨入库，4调拨出库
 * @property string $create_date 创建时间
 */
class OutStockLogs extends \yii\db\ActiveRecord
{
    public $status_array = [
        1=>'采购入库',
        2=>'销售出库',
        3=>'调拨入库',
        4=>'调拨出库'
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['qty', 'uid', 'log_type'], 'integer'],
            [['cost'], 'number'],
            [['create_date'], 'safe'],
            [['sku'], 'string', 'max' => 13],
            [['order_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sku' => 'Sku',
            'order_id' => 'Order ID',
            'qty' => '数量',
            'cost' => '成本',
            'uid' => '操作人',
            'log_type' => '类别',
            'create_date' => '操作时间',
        ];
    }
}
