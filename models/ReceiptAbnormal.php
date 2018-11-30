<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "receipt_abnormal".
 *
 * @property int $id 异常收货单id
 * @property string $track_number 物流单号
 * @property string $contents 反馈内容
 * @property int $status 0未处理，1处理完成，2处理中
 * @property int $create_uid 创建者id
 * @property string $create_time 创建时间
 */
class ReceiptAbnormal extends \yii\db\ActiveRecord
{
    public $status_array = [
        0 => '待采购处理',
        1 => '待库房处理',
        2 => '处理完成'
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'receipt_abnormal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['track_number', 'contents'], 'required'],
            [['status', 'create_uid'], 'integer'],
            [['create_time'], 'safe'],
            [['track_number'], 'string', 'max' => 255],
            [['contents'], 'string', 'max' => 99999],
            [['track_number'], 'uniqueTrack_number'],
        ];
    }
    // 自定义track_number的验证，
    public function uniqueTrack_number($attribute, $params)
    {
        $sql = "select platform_track from purchases where platform_track like '%$this->track_number%'";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        if (!empty($res)) {
            $this->addError($attribute, "该物流单号存在对应的收货单，请在收货单中的'预收货完成'提交反馈！");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'track_number' => '物流单号',
            'contents' => '反馈内容',
            'status' => '状态',
            'create_uid' => '创建人',
            'create_time' => '创建时间',
        ];
    }
}
