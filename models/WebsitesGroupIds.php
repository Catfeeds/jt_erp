<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "websites_group_ids".
 *
 * @property int $id
 * @property int $group_id 产品组合编号
 * @property int $website_id 产品ID
 * @property int $qty 数量
 * @property string $price 单价
 */
class WebsitesGroupIds extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'websites_group_ids';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group_id', 'website_id', 'price'], 'required'],
            [['group_id', 'website_id', 'qty'], 'integer'],
            [['price'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => '产品组合编号',
            'website_id' => '产品ID',
            'qty' => '数量',
            'price' => '单价',
        ];
    }
}
