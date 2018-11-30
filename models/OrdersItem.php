<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders_item".
 *
 * @property int $id
 * @property int $order_id 订单ID
 * @property string $sku
 * @property int $qty 购买数量
 * @property string $price 单价
 * @property string $color 颜色属性
 * @property string $size 尺寸属性
 * @property string $image SKU对应图
 */
class OrdersItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'qty', 'price'], 'required'],
            [['order_id', 'qty'], 'integer'],
            [['price'], 'number'],
            [['sku', 'color', 'size', 'image'], 'string', 'max' => 255],
        ];
    }

    public function getOrder(){
        return Orders::findOne($this->order_id);
    }

    public function getProduct(){
        return ProductsVariant::find()->where(['sku' => $this->sku])->one();
    }

    public static function getProductBySku($sku){
        return ProductsVariant::find()->where(['sku' => $sku])->one();
    }

    public static function getProductBySpu($spu){
        return ProductsVariant::find()->where(['spu' => $spu])->one();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => '订单编号',
            'sku' => 'SKU',
            'qty' => '数量',
            'price' => '价格',
            'color' => '颜色',
            'size' => '尺寸',
            'image' => 'Image',
        ];
    }

    /**
     * 获取订单详情信息
     * @param $order_id
     * @return array
     */
    public static function get_order_item($order_id)
    {
        $sql = "select * from orders_item where order_id =".$order_id;
        return Yii::$app->db->createCommand($sql)->queryAll();
    }

}
