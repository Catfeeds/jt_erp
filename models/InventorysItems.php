<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventorys_items".
 *
 * @property int $id
 * @property int $inventory_id 盘存单ID
 * @property string $location_code 库位代码
 * @property string $sku
 * @property string $inventory_qty 盘点数量
 * @property string $stock_qty 库位库存数量
 * @property string $difference_qty 差异
 */
class InventorysItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventorys_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['inventory_id', 'location_code', 'sku', 'inventory_qty'], 'required'],
            [['inventory_id'], 'integer'],
            [['inventory_qty', 'stock_qty', 'difference_qty'], 'number'],
            [['location_code', 'sku'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inventory_id' => '盘存单ID',
            'location_code' => '库位代码',
            'sku' => 'Sku',
            'inventory_qty' => '盘点数量',
            'stock_qty' => '库位库存数量',
            'difference_qty' => '差异',
        ];
    }
}
