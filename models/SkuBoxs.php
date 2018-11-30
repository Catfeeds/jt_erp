<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sku_boxs".
 *
 * @property int $id
 * @property string $p_sku 主SKU
 * @property string $s_sku 附SKU
 * @property int $status 状态1启用 0取消
 * @property string $create_date 添加时间
 * @property int $uid 添加人
 */
class SkuBoxs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sku_boxs';
    }

    public $status_array = [
        1 => '启用',
        0 => '禁用'
    ];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['p_sku', 's_sku', 'uid'], 'required'],
            [['status', 'uid'], 'integer'],
            [['create_date'], 'safe'],
            [['p_sku', 's_sku'], 'string', 'max' => 13],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'p_sku' => '主SKU',
            's_sku' => '附SKU',
            'status' => '状态',
            'create_date' => '添加时间',
            'uid' => '添加人',
        ];
    }

    /**
     * 获取主SKU
     * @param $sku
     * @return mixed
     */
    public function getSkuBys($sku)
    {
        $data = $this->find()->where(['s_sku' => $sku, 'status' => 1])->one();
        if($data)
        {
            return $data->p_sku;
        }else{
            return $sku;
//            $data = $this->find()->where(['p_sku' => $sku, 'status' => 0])->one();
//            if($data)
//            {
//                return $data->s_sku;
//            }else{
//                return $sku;
//            }

        }
    }

}
