<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "purchases_items".
 *
 * @property int $id
 * @property string $purchase_number 采购单号
 * @property string $sku` SKU
 * @property string $qty 数量
 * @property string $price 单价
 * @property string $buy_link 购买链接
 * @property string $info 说明
 * @property string $delivery_qty 实收数量
 * @property string $refound_qty 退货数量
 * @property int $delivery_uid 收货人
 *
 */
class PurchasesItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_items';
    }
    public function getSku_info(){
        return $this->hasOne(ProductsVariant::className(), ['sku' => 'sku']);
    }
    public function getPurchaes_info(){
        return $this->hasOne(Purchases::className(), ['order_number' => 'purchase_number']);
    }

}
