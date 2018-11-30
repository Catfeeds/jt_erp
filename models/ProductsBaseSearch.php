<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProductsBase;
use app\models\User;


/**
 * ProductsBaseSearch represents the model behind the search form of `app\models\ProductsBase`.
 */
class ProductsBaseSearch extends ProductsBase
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'categorie', 'product_type', 'sex', 'uid', 'open'], 'integer'],
            [['title', 'spu', 'image', 'declaration_hs', 'create_time','cn_name','en_name'], 'safe'],
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
        $query = ProductsBase::find();
        #,'categories.cn_name'
        $query = $query->select('products_base.*');

        // add conditions that should always apply here
       //$query ->leftJoin('categories','categories.id = products_base.categorie');
//       $query->joinWith(['categories_info']);
        $userMedel = new User();
        $is_admin = $userMedel->getGroupUsers();

        if($is_admin['is_admin'] == 0 && $is_admin['is_purchase'] == 0) {
            if($is_admin['leader'] < 1) {
                $query->where('products_base.uid = '.Yii::$app->user->getId());
            } else {
                if($is_admin['data']) {
                    $uid_arr = array();
                    foreach ($is_admin['data'] as $row) {
                        $uid_arr[] = $row['id'];
                    }
                    $query->where('products_base.uid in ('.implode(',',$uid_arr).')');
                }
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        //echo $query ->createCommand()->getRawSql();exit;


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $orderTimeBegin = null;
        $orderTimeEnd = null;
        if (!empty($params["order_time_begin"]))
        {
            $orderTimeBegin = $params["order_time_begin"] . " 00:00:00";
        }

        if (!empty($params["order_time_end"]))
        {
            $orderTimeEnd = $params["order_time_end"] . " 23:59:59";
        }

        if (!is_null($orderTimeBegin))
        {
            $query->andFilterWhere(['>=', 'create_time', $orderTimeBegin]);
        }

        if (!is_null($orderTimeEnd))
        {
            $query->andFilterWhere(['<=', 'create_time', $orderTimeEnd]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'products_base.id' => $this->id,
            'product_type' => $this->product_type,
            'sex' => $this->sex,
            'uid' => $this->uid,
            'open' => $this->open
        ]);
        $query->andFilterWhere(['>=', 'products_base.create_time', $this->create_time]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'en_name', $this->en_name])
            ->andFilterWhere(['like', 'products_base.spu', $this->spu])
            ->andFilterWhere(['like', 'declaration_hs', $this->declaration_hs]);

        return $dataProvider;
    }

}
