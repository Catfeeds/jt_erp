<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\StockLogs;

/**
 * StockLogsSearch represents the model behind the search form of `app\models\StockLogs`.
 */
class StockLogsSearch extends StockLogs
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'qty', 'uid', 'log_type'], 'integer'],
            [['sku', 'order_id', 'create_date', 'start_date', 'end_date'], 'safe'],
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
        $query = StockLogs::find();

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
//        $query->andFilterWhere(['in', 'log_type',[1,3]]);
        $query->andFilterWhere([
//            'id' => $this->id,
            'qty' => $this->qty,
            'cost' => $this->cost,
            'uid' => $this->uid,
            'log_type' => $this->log_type,
        ]);

        ###订单编号批量查询
        if (isset($params['StockLogsSearch']['order_id']) && $params['StockLogsSearch']['order_id'] )
        {
            $order_ids = explode(PHP_EOL,$params['StockLogsSearch']['order_id']);
            $query->andFilterWhere(['in', 'order_id', $order_ids]);
        }
        if(!empty($params['country']))
        {
            $id_arr = Orders::find()->select('id')->where(array('country' => $params['country']))->asArray()->all();
            if ($id_arr)
            {
                $query->andFilterWhere(['in', 'order_id', array_unique(array_column($id_arr,'id'))]);
            }
            else
            {
                $query->andFilterWhere(['in', 'order_id', array(0)]);
            }
        }
        if(!empty($this->start_date)){
            $query->andFilterWhere(['>=', 'create_date', $this->start_date]);
        }
        if(!empty($this->end_date)){
            $query->andFilterWhere(['<=', 'create_date', $this->end_date]);
        }

        $query->andFilterWhere(['like', 'sku', $this->sku]);
//            ->andFilterWhere(['like', 'order_id', $this->order_id]);
//        var_dump($query->createCommand()->getRawSql());
        return $dataProvider;
    }
}
