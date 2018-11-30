<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "back_logs".
 *
 * @property int $id
 * @property int $back_id 退库id
 * @property int $create_uid 操作人
 * @property int $status 操作人操作变更的状态，例：由1变为2，那就是2
 * @property string $records 操作记录
 * @property string $create_time 创建时间
 */
class BackLogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'back_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['back_id', 'create_uid', 'status'], 'integer'],
            [['records'], 'required'],
            [['create_time'], 'safe'],
            [['records'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'back_id' => 'Back ID',
            'create_uid' => 'Create Uid',
            'status' => 'Status',
            'records' => 'Records',
            'create_time' => 'Create Time',
        ];
    }
}
