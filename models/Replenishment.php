<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "replenishment".
 *
 * @property int $id
 * @property int $orders_id 订单ID
 * @property string $sku_id sku
 * @property int $supplement_number 补充数量
 * @property string $status 已采购 /未采购
 * @property string $create_time
 */
class Replenishment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'replenishment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orders_id', 'supplement_number'], 'integer'],
            [['create_time'], 'safe'],
            [['sku_id', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orders_id' => '订单ID',
            'sku_id' => 'sku',
            'supplement_number' => '补充数量',
            'status' => '已采购 /未采购',
            'create_time' => '添加时间',
        ];
    }
}
