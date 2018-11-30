<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "products_suppliers".
 *
 * @property int $id
 * @property int $supplier_id 供应商ID
 * @property string $sku 产品变体编码
 * @property string $url 采购链接
 * @property int $min_buy 最小起订量
 * @property string $price 采购价
 * @property int $deliver_time 发货周期
 */
class ProductsSuppliers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products_suppliers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'supplier_id', 'sku'], 'required'],
            [[ 'supplier_id', 'min_buy', 'deliver_time'], 'integer'],
            [['url'], 'string'],
            [['price'], 'number'],
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
            'supplier_id' => '供应商ID',
            'sku' => '产品变体编码',
            'url' => '采购链接',
            'min_buy' => '最小起订量',
            'price' => '采购价',
            'deliver_time' => '发货周期',
        ];
    }

    /**
     * @param $sku
     * @return mixed|string
     */
    static public function getUrl($sku)
    {
        $suppliers = ProductsSuppliers::find()->select('url')->where(['sku' => $sku])->one();
        if($suppliers)
        {
            return $suppliers->url;
        }
        return '';
    }

}
