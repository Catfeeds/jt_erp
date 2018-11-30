<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property int $level 分类等级，产品分类共分三级，一级些值默认0
 * @property int $pid 上级分类ID
 * @property string $cn_name 中文名称，通常ERP后台显示用
 * @property string $en_name 英文名称，通常是用来做FEED用
 * @property string $create_time 添加时间
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level', 'pid'], 'integer'],
            [['cn_name', 'en_name'], 'required'],
            [['create_time'], 'safe'],
            [['cn_name', 'en_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'level' => '分类等级',
            'pid' => '上级分类',
            'cn_name' => '中文名称',
            'en_name' => '英文名称',
            'create_time' => '添加时间',
        ];
    }
}
