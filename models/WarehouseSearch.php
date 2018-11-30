<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Warehouse;

/**
 * WarehouseSearch represents the model behind the search form of `app\models\Warehouse`.
 */
class WarehouseSearch extends Warehouse
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'stock_type', 'uid', 'status'], 'integer'],
            [['stock_name', 'stock_code', 'create_date'], 'safe'],
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
        $query = Warehouse::find();

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
            'stock_type' => $this->stock_type,
            'create_date' => $this->create_date,
            'uid' => $this->uid,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'stock_name', $this->stock_name])
            ->andFilterWhere(['like', 'stock_code', $this->stock_code]);

        return $dataProvider;
    }
}
