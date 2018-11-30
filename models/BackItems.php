<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "back_items".
 *
 * @property int $id
 * @property int $back_id 退库单id
 * @property string $sku 退库sku
 * @property int $qty 退库数量
 * @property string $notes 备注
 */
class BackItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'back_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'back_id', 'qty'], 'integer'],
            [['sku'], 'string', 'max' => 50],
            [['notes'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'back_id' => 'Back ID',
            'sku' => 'Sku',
            'qty' => 'Qty',
            'notes' => 'Notes',
        ];
    }
}
