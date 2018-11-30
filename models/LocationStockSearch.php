<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LocationStock;

/**
 * LocationStockSearch represents the model behind the search form of `app\models\LocationStock`.
 */
class LocationStockSearch extends LocationStock
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'stock'], 'integer'],
            [['stock_code', 'area_code', 'location_code', 'sku', 'create_date', 'update_date'], 'safe'],
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
        $query = LocationStock::find();

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
            'stock' => $this->stock,
            'create_date' => $this->create_date,
            'update_date' => $this->update_date,
        ]);

        $query->andFilterWhere(['like', 'stock_code', $this->stock_code])
            ->andFilterWhere(['like', 'area_code', $this->area_code])
            ->andFilterWhere(['like', 'location_code', $this->location_code])
            ->andFilterWhere(['like', 'sku', $this->sku]);

        return $dataProvider;
    }
}
