<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "receipt_logs".
 *
 * @property int $id
 * @property string $track_number 快递单号
 * @property string $create_date 创建时间
 * @property int $create_uid 创建人
 * @property string $comment 备注
 * @property int $status 状态 0收货中 1完成 2异常 3取消
 */
class ReceiptLogs extends \yii\db\ActiveRecord
{
    public $status_array = [0=>'收货中', 1=>'完成', 2=>'异常', 3=>'取消'];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'receipt_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['track_number', 'create_uid'], 'required'],
            [['create_date'], 'safe'],
            [['create_uid', 'status'], 'integer'],
            [['comment'], 'string'],
            [['track_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'track_number' => '快递单号',
            'create_date' => '生成时间',
            'create_uid' => '操作人',
            'comment' => '备注',
            'status' => '状态',
        ];
    }
}
