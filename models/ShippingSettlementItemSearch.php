<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ShippingSettlementItemSearch represents the model behind the search form of `app\models\ShippingSettlementItem`.
 */
class ShippingSettlementItemSearch extends ShippingSettlementItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_shipping_settlement', 'id_order'], 'integer'],
            [['lc_number', 'currency', 'id_shipping_settlement', 'id_order'], 'safe'],
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
        $query = ShippingSettlementItem::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id_order' => SORT_DESC]]
        ]);
        if (isset($params['ShippingOrderSettlementSearch']))
        {
            $shipping_order_settlement_params = $params['ShippingOrderSettlementSearch'];
            var_dump($shipping_order_settlement_params);die;
            $orderQuery = ShippingSettlement::find();
            $orderQuery->andFilterWhere(['status' => 2])
                ->andFilterWhere([]);

            ##SPU搜索
            if(!empty($params["spu"]))
            {
                $query->andFilterWhere(['like','sku',$params["spu"]]);
            }
            $order_ids = $orderQuery->select('id')->asArray()->all();
            if($order_ids)
            {
                $ids = [];
                foreach($order_ids as $oid)
                {
                    $ids[]=$oid['id'];
                }
                $query->andFilterWhere(['in', 'order_id', $ids]);
            }
            else
            {
                $query->andFilterWhere(['in', 'order_id', array(0)]);
            }
        }else{
            $this->load($params);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'lc_number', $this->lc_number]);
        return $dataProvider;
    }
}
