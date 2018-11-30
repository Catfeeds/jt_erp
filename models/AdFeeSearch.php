<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AdFee;

/**
 * AdFeeSearch represents the model behind the search form of `app\models\AdFee`.
 */
class AdFeeSearch extends AdFee
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'website_id'], 'integer'],
            [['ad_total'], 'number'],
            [['ad_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = AdFee::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
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
            'website_id' => $this->website_id,
            'ad_total' => $this->ad_total,
            'ad_date' => $this->ad_date,
        ]);

        return $dataProvider;
    }
}
