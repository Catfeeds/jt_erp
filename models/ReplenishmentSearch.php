<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Replenishment;

/**
 * ReplenishmentSearch represents the model behind the search form of `app\models\Replenishment`.
 */
class ReplenishmentSearch extends Replenishment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'orders_id', 'supplement_number'], 'integer'],
            [['sku_id', 'status', 'create_time'], 'safe'],
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
        $query = Replenishment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 1000,
            ],
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
            'orders_id' => $this->orders_id,
            'supplement_number' => $this->supplement_number,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'sku_id', $this->sku_id])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }

}
