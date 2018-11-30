<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OutStockLogs;

/**
 * OutStockLogsSearch represents the model behind the search form of `app\models\OutStockLogs`.
 */
class OutStockLogsSearch extends OutStockLogs
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'qty', 'uid', 'log_type'], 'integer'],
            [['sku', 'order_id', 'create_date'], 'safe'],
            [['cost'], 'number'],
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
        $query = OutStockLogs::find();

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
        $query->andFilterWhere(['in', 'log_type',[2,4]]);
        $query->andFilterWhere([
            'id' => $this->id,
            'qty' => $this->qty,
            'cost' => $this->cost,
            'uid' => $this->uid,
            'log_type' => $this->log_type,
            'create_date' => $this->create_date,
        ]);

        $query->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'order_id', $this->order_id]);

        return $dataProvider;
    }
}
