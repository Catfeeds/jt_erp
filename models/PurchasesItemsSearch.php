<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Purchases;

/**
 * PurchasesSearch represents the model behind the search form of `app\models\Purchases`.
 */
class PurchasesItemsSearch extends PurchasesItems
{
    public $spu;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'qty', 'price','delivery_qty','refound_qty'], 'integer'],
            [['purchase_number', 'spu', 'qty', 'price', 'buy_link', 'delivery_qty','refound_qty'], 'safe'],
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
        $queryItem = PurchasesItems::find('purchase_items.*,products_variant.color,products_variant.size');

        $queryItem->joinWith(['sku_info']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $queryItem,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $query = Purchases::find();
        if (isset($params['PurchasesSearch']))
        {
            $search_params = $params['PurchasesSearch'];
            if (isset($search_params['order_number']) && $search_params['order_number'])
            {
                $query->andFilterWhere(['like','order_number',$search_params['order_number']]);
            }
            if (isset($search_params['supplier']) && $search_params['supplier'])
            {
                $query->andFilterWhere(['like','supplier',$search_params['supplier']]);
            }
            if (isset($search_params['platform']) && $search_params['platform'])
            {
                $query->andFilterWhere(['like','platform',$search_params['platform']]);
            }
            if (isset($search_params['platform_order']) && $search_params['platform_order'])
            {
                $query->andFilterWhere(['like','platform_order',$search_params['platform_order']]);
            }
            if (isset($search_params['platform_track']) && $search_params['platform_track'])
            {
                $query->andFilterWhere(['like','platform_track',$search_params['platform_track']]);
            }
            if (isset($search_params['amaount']) && $search_params['amaount'])
            {
                $query->andFilterWhere(['amaount' => $search_params['amaount']]);
            }
            if (isset($search_params['status']) && $search_params['status'] != '')
            {
                $query->andFilterWhere(['status' => $search_params['status']]);
            }
            if (isset($search_params['delivery_time']) && $search_params['delivery_time'])
            {
                $query->andFilterWhere(['>=','delivery_time', $search_params['delivery_time']]);
            }
            if (isset($search_params['create_time']) && $search_params['create_time'])
            {
                $query->andFilterWhere(['>=','create_time',$search_params['create_time']]);
            }
            $purchase_number_arr = $query->select('order_number')->asArray()->all();

            if ($purchase_number_arr)
            {
                $queryItem->andFilterWhere(['in', 'purchase_number', array_unique(array_values(array_column($purchase_number_arr,'order_number')))]);
            }
            else
            {
                $queryItem->andFilterWhere(['in', 'purchase_number', array(0)]);
            }
        }
        else
        {
            $this->load($params);
        }

        if (!$this->validate()) {
            return $dataProvider;
        }

        $queryItem->andFilterWhere(['like', 'sku', $this->spu]);
        return $dataProvider;
    }

}
