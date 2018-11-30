<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stock_location_code".
 *
 * @property int $id
 * @property string $stock_code 仓库编号
 * @property string $area_code 库区编号
 * @property string $code 库位编号
 * @property string $info 库位说明
 * @property int $uid 操作人
 * @property string $create_date 创建时间
 */
class StockLocationCode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock_location_code';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['uid'], 'integer'],
            [['create_date'], 'safe'],
            [['stock_code'], 'string', 'max' => 100],
            [['area_code', 'code', 'info'], 'string', 'max' => 50],
            [['stock_code', 'code'], 'unique', 'targetAttribute' => ['stock_code', 'code']],
            [['code'] ,'match','pattern'=>'/^[0-9A-Za-z]+$/','message' => '库位编码 必须为字母跟数字组合'],

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
            'code' => '库位编号',
            'info' => '库位说明',
            'uid' => '操作人',
            'create_date' => 'Create Date',
        ];
    }
    public function getUser_info(){
        return $this->hasOne(User::className(), ['id' => 'uid']);
    }
}
