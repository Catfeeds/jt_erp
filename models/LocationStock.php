<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "location_stock".
 *
 * @property int $id
 * @property string $stock_code 库存CODE
 * @property string $area_code 库区CODE
 * @property string $location_code 库位CODE
 * @property string $sku sku
 * @property int $stock 数量
 * @property string $create_date 创建时间
 * @property string $update_date 最后更新时间
 */
class LocationStock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'location_stock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stock_code', 'area_code', 'location_code', 'sku'], 'required'],
            [['stock'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
            [['stock_code'], 'string', 'max' => 100],
            [['area_code', 'location_code'], 'string', 'max' => 50],
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
            'stock_code' => '仓库编号',
            'area_code' => '库区编号',
            'location_code' => '库位编号',
            'sku' => 'SKU',
            'stock' => '数量',
            'create_date' => '添加时间',
            'update_date' => '最后更新时间',
        ];
    }
}
