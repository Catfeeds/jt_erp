<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "receipt_feedback".
 * 收货反馈日志表
 * jieson 2018.09.29
 *
 * @property int $id
 * @property int $receipt_id 上架id
 * @property string $order_numer 采购单号
 * @property string $contents 反馈内容
 * @property int $create_uid 操作人
 * @property int $status 反馈状态 1正常，2异常
 * @property timestamp $create_time 创建时间
 * @property timestamp $update_time 更新时间
 * 
 */
class ReceiptFeedback extends \yii\db\ActiveRecord
{
    public $status_array = [
        1 => '正常',
        2 => '异常',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'receipt_feedback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_number', 'contents'], 'required'],
            [['create_uid', 'status', 'type'], 'integer'],
            [['contents'], 'string'],
            // [['create_time', 'update_time'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [

            'order_number' => '采购单号',
            'contents'     => '反馈内容',
            'status'       => '状态',
            'track_number' => '快递单号'

        ];
    }

}
