<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "websites_sku".
 *
 * @property int $id
 * @property int $website_id
 * @property string $sku SKU
 * @property string $color 颜色
 * @property string $size 尺寸
 * @property string $sign
 * @property string $images 图片
 * @property int $out_stock 库存状态，1有，0无，无库存的前台无法选中相关属性组合
 */
class WebsitesSku extends \yii\db\ActiveRecord
{
    public $stocks = [
        0 => '下架',
        1 => '上架'
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'websites_sku';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['website_id'], 'required'],
            [['website_id', 'out_stock'], 'integer'],
            [['sku'], 'string', 'max' => 13],
            [['color', 'size', 'images'], 'string', 'max' => 255],
            [['sign'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'website_id' => 'Website ID',
            'sku' => 'SKU',
            'color' => '颜色',
            'size' => '尺寸',
            'sign' => 'Sign',
            'images' => '图片',
            'out_stock' => '库存状态',
        ];
    }

    public function ProductsSkuColor($website_id){
        $website = Websites::findOne($website_id);
        $spu = $website->spu;
        if($colors = Yii::$app->db->createCommand("select id,color from products_variant where spu = '{$spu}' GROUP BY color")->queryAll()){
            $res = [];
            foreach ($colors as $color){
                $res[$color['color']] = $color['color'] ? $color['color'] : '无';
            }
            return $res;
        }else{
            return [];
        }
    }

    public function ProductsSkuSize($website_id){
        $website = Websites::findOne($website_id);
        $spu = $website->spu;
        if($sizes = Yii::$app->db->createCommand("select id,`size` from products_variant where spu = '{$spu}' GROUP BY `size`")->queryAll()){
            $res = [];
            foreach ($sizes as $size){
                $res[$size['size']] = $size['size'] ? $size['size'] : '无';
            }
            return $res;
        }else{
            return [];
        }
    }

}
