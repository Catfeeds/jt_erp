<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ShippingOrderSettlementSearch represents the model behind the search form of `app\models\ShippingOrderSettlement`.
 */
class ShippingOrderSettlementSearch extends ShippingOrderSettlement
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status','id_order',], 'integer'],
            [['id_order', 'lc_number','back_order_total', 'other_fee', 'currency'], 'safe'],
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
        $query = ShippingOrderSettlement::find();

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
            'id_order' => $this->id_order,
            'lc_number' => $this->lc_number,
            'currency' => $this->currency,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'lc_number', $this->lc_number])
            ->andFilterWhere(['like', 'currency', $this->currency]);

        return $dataProvider;
    }

}
