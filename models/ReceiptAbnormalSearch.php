<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ReceiptAbnormal;

/**
 * ReceiptAbnormalSearch represents the model behind the search form of `app\models\ReceiptAbnormal`.
 */
class ReceiptAbnormalSearch extends ReceiptAbnormal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'create_uid'], 'integer'],
            [['track_number', 'contents', 'create_time'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ReceiptAbnormal::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'create_uid' => $this->create_uid,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'track_number', $this->track_number])
            ->andFilterWhere(['like', 'contents', $this->contents]);

        return $dataProvider;
    }
}