<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventorys".
 *
 * @property int $id
 * @property string $stock 仓库
 * @property string $inventory_date 盘存时间
 * @property string $create_time 添加时间
 * @property int $create_uid 添加人
 * @property int $order_status 状态 0草稿，1已确认 2已更新库存
 * @property string $comments 说明
 * @property int $is_all 是否全盘 1.部分盘 2.全盘
 */
class Inventorys extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventorys';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['inventory_date', 'create_uid'], 'required'],
            [['inventory_date', 'create_time'], 'safe'],
            [['create_uid', 'order_status','is_all'], 'integer'],
            [['comments'], 'string'],
            [['stock'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stock' => '仓库',
            'inventory_date' => '盘存时间',
            'create_time' => '添加时间',
            'create_uid' => '添加人',
            'order_status' => '状态',
            'is_all' => '是否全盘',
            'comments' => '说明',
        ];
    }

    public $status_array = [
        0=>'草稿',
        1=>'已确认',
        2=>'已更新库存'
    ];

    static public $status_arr = [
        0=>'草稿',
        1=>'已确认',
        2=>'已更新库存'
    ];

    static public $is_all_arr = [
        0 => '所有',
        1=> '否',
        2=> '是',
    ];

}
