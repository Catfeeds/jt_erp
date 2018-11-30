<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "products_variant".
 *
 * @property int $id
 * @property string $spu spu
 * @property string $color 颜色
 * @property string $size 尺寸
 * @property string $sku 产品变体编号SPU+5位，共13位
 * @property string $image 图片
 * @property string $create_time 添加时间
 */
class ProductsVariant extends \yii\db\ActiveRecord
{
    public $images_json;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products_variant';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['spu', 'sku'], 'required'],
            [['image'], 'string'],
            [['create_time'], 'safe'],
            [['spu'], 'string', 'max' => 8],
            [['color', 'size'], 'string', 'max' => 255],
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
            'spu' => '产品主编号',
            'color' => '颜色',
            'size' => '尺寸',
            'sku' => '产品变体编号',#SPU+5位，共13位
            'image' => '图片',
            'create_time' => '添加时间',
        ];
    }


    /**
     * 获取采购链接
     * @return mixed|string
     */
    public function getBuyLink()
    {
        $spu = substr($this->sku, 0 ,8);
        $suppliers = ProductsSuppliers::find()->where(['sku' => $this->sku])->one();
        if($suppliers)
        {
            return $suppliers->url;
        }else{
            $suppliers = ProductsSuppliers::find()->where(['like', 'sku', $spu.'%'])->one();
            if($suppliers)
            {
                return $suppliers->url;
            }else{
                return '#';
            }
        }
    }

    public function getProduct(){
        $product = ProductsBase::find()->where(['spu' => $this->spu])->one();
        return $product;

    }

}
