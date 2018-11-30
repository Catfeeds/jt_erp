<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stock_location_area".
 *
 * @property int $id
 * @property string $stock_code 仓库编号
 * @property string $area_code 库区编号
 * @property string $area_name 库区名称
 * @property int $uid 操作人
 * @property string $create_date 创建时间
 */
class StockLocationArea extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock_location_area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'area_code', 'area_name'], 'required'],
            [['uid'], 'integer'],
            [['create_date'], 'safe'],
            [['area_code', 'area_name'], 'string', 'max' => 50],
            [['area_code'] ,'match','pattern'=>'/^[0-9A-Za-z]+$/','message' => '库区编码 必须为字母跟数字组合'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stock_code' => '仓库编号',
            'area_code' => '库区编号',
            'area_name' => '库区名称',
            'uid' => '操作人',
            'create_date' => 'Create Date',
        ];
    }
    public function getUser_info(){
        return $this->hasOne(User::className(), ['id' => 'uid']);
    }
}
