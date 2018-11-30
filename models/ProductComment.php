<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_comment".
 *
 * @property int $id
 * @property int $website_id 产品ID
 * @property string $name 姓名
 * @property string $phone 电话
 * @property string $body 评论内容
 * @property string $ip IP
 * @property int $isshow 是否显示
 * @property string $add_time 发布时间
 */
class ProductComment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['website_id', 'add_time'], 'required'],
            [['isshow'], 'integer'],
            [['add_time'], 'safe'],
            [['name', 'phone', 'ip'], 'string', 'max' => 50],
            [['body'], 'string', 'max' => 10000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'website_id' => '站点ID',
            'name' => '称呼',
            'phone' => '电话',
            'body' => '评论内容',
            'ip' => 'Ip地址',
            'isshow' => '是否显示',
            'add_time' => '发布时间',
        ];
    }
}
