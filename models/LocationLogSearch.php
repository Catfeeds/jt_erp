<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LocationLog;

/**
 * LocationLogSearch represents the model behind the search form of `app\models\LocationLog`.
 */
class LocationLogSearch extends LocationLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'qty', 'uid'], 'integer'],
            [['order_id', 'sku', 'stock_code', 'location_code', 'create_date','type','start_date', 'end_date'], 'safe'],
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
        $query = LocationLog::find();

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
        ###订单编号批量查询
        if (isset($params['LocationLogSearch']['order_id']) && $params['LocationLogSearch']['order_id'] )
        {
            $order_ids = explode(PHP_EOL,$params['LocationLogSearch']['order_id']);
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
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'qty' => $this->qty,
            'uid' => $this->uid,
            'create_date' => $this->create_date,
            'type' => $this->type,

        ]);

        //$query->andFilterWhere(['like', 'order_id', $this->order_id])
            //->andFilterWhere(['like', 'sku', $this->sku])
        $query->andFilterWhere(['like', 'stock_code', $this->stock_code])
            ->andFilterWhere(['like', 'location_code', $this->location_code]);

        return $dataProvider;
    }
}
