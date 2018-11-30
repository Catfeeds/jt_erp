<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ShippingLogisticsStateSearch represents the model behind the search form of `app\models\ShippingLogisticsState`.
 */
class ShippingLogisticsStateSearch extends ShippingLogisticsState
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_order', 'state','type'], 'integer'],
            [['lc_number', 'country', 'created_at', 'update_at','lc'], 'safe'],
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
        $query = ShippingLogisticsState::find();

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
            'country' => $this->country,
            'state' => $this->state,
            'type' => $this->type
        ]);

        $query->andFilterWhere(['like', 'lc_number', $this->lc_number])
            ->andFilterWhere(['like', 'lc', $this->lc]);

        return $dataProvider;
    }
}
