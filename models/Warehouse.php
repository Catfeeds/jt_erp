<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "warehouse".
 *
 * @property int $id id
 * @property string $stock_name 仓库名称
 * @property string $stock_code 仓库代码，最多10个字符，通常为A,B,C,A1,B1
 * @property int $stock_type 仓库类型：1普通，2转运仓库
 * @property string $create_date 创建时间
 * @property int $uid
 * @property int $status 状态 1可用，0禁用
 */
class Warehouse extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'warehouse';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stock_name', 'stock_code', 'uid'], 'required'],
            [['stock_type', 'uid', 'status'], 'integer'],
            [['create_date'], 'safe'],
            [['stock_name'], 'string', 'max' => 255],
            [['stock_code'], 'string', 'max' => 10],
            [['stock_code'] ,'match','pattern'=>'/^[0-9A-Za-z]+$/','message' => '仓库编码 必须为字母跟数字组合'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'stock_name' => '仓库名称',
            'stock_code' => '仓库代码，最多10个字符，通常为A,B,C,A1,B1',
            'stock_type' => '仓库类型：1普通，2转运仓库',
            'create_date' => '创建时间',
            'uid' => 'Uid',
            'status' => '状态 1可用，0禁用',
        ];
    }

    public function getUser_info(){
        return $this->hasOne(User::className(), ['id' => 'uid']);
    }

    /**
     * @return array
     * 普通仓库代码
     */
    public function stockCods()
    {
        $data = $this->find()->where(['stock_type' => 1])->all();
        $cods = [];
        foreach ($data as $v)
        {
            $cods[$v->stock_code] = $v->stock_code;
        }
        return $cods;
    }

    /**
     * @return array
     * 转寄仓
     */
    public function stockForward()
    {
        $data = $this->find()->where(['stock_type' => 2])->all();
        $cods = [];
        foreach ($data as $v)
        {
            $cods[$v->stock_code] = $v->stock_name;
        }
        return $cods;
    }

}
