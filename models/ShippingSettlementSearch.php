<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ShippingSettlementSearch represents the model behind the search form of `app\models\ShippingSettlement`.
 */
class ShippingSettlementSearch extends ShippingSettlement
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['lc', 'back_total', 'other_fee', 'currency','settlement_number'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
        $query = ShippingSettlement::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'lc' => $this->lc,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'settlement_number', $this->settlement_number])
            ->andFilterWhere(['like', 'lc', $this->lc]);

        return $dataProvider;
    }
}
