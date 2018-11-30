<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requisitions_items".
 *
 * @property int $id
 * @property int $req_id
 * @property string $sku
 * @property string $qty
 */
class RequisitionsItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'requisitions_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['req_id', 'sku'], 'required'],
            [['req_id'], 'integer'],
            [['qty'], 'number'],
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
            'req_id' => 'Req ID',
            'sku' => 'Sku',
            'qty' => 'Qty',
        ];
    }
    public function getSku_info(){
        return $this->hasOne(ProductsVariant::className(), ['sku' => 'sku']);
    }
}
