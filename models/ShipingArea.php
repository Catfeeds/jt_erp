<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shiping_area".
 *
 * @property int $id
 * @property string $province 省
 * @property string $city 市
 * @property string $area 区
 * @property string $post_code 邮编
 * @property int $status 状态 1启用 0禁用
 * @property string $country 国家
 */
class ShipingArea extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shiping_area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['country'], 'required'],
            [['province', 'city', 'area'], 'string', 'max' => 255],
            [['post_code'], 'string', 'max' => 50],
            [['country'], 'string', 'max' => 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'province' => '省',
            'city' => '市',
            'area' => '区',
            'post_code' => '邮编',
            'status' => '状态 1启用 0禁用',
            'country' => '国家',
        ];
    }
}
