<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ad_fee".
 *
 * @property int $id
 * @property int $website_id 站点ID
 * @property string $ad_total 广告总额
 * @property string $ad_date 广告费用日期
 */
class AdFee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ad_fee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['website_id', 'ad_date'], 'required'],
            [['website_id'], 'integer'],
            [['ad_total'], 'number'],
            [['ad_date'], 'safe'],
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
            'ad_total' => 'Ad Total',
            'ad_date' => 'Ad Date',
        ];
    }
}
