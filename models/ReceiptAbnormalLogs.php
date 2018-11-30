<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "receipt_abnormal_logs".
 *
 * @property int $id
 * @property int $receipt_abnormal_id 异常收货单id
 * @property string $dealContents 处理内容
 * @property int $create_uid 回复人id
 * @property string $create_time
 */
class ReceiptAbnormalLogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'receipt_abnormal_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['receipt_abnormal_id', 'create_uid', 'type'], 'integer'],
            [['create_time'], 'safe'],
            [['dealContents'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'receipt_abnormal_id' => '异常收货单id',
            'dealContents'        => '处理回复',
            'create_uid'          => '回复用户id',
            'create_time'         => '创建时间',
            'type'                => '回复方',
        ];
    }
}
