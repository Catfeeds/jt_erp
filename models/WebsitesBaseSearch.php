<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Websites;

/**
 * WebsitesBaseSearch represents the model behind the search form of `app\models\Websites`.
 */
class WebsitesBaseSearch extends Websites
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sale_end_hours', 'uid', 'designer', 'is_ads', 'ads_user', 'is_group'], 'integer'],
            [['title', 'info', 'images', 'spu','facebook', 'google', 'other', 'product_style_title', 'product_style', 'related_id', 'size', 'sale_city', 'domain', 'host', 'theme', 'ads_time', 'create_time', 'sale_info', 'additional', 'think', 'update_time', 'disable', 'spu'], 'safe'],
            [['sale_price', 'price', 'next_price'], 'number'],
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
        $query = Websites::find();

        // add conditions that should always apply here
        $userModel = new User();
        $is_admin = $userModel->getGroup();

        if( $is_admin['is_admin'] == 0)
        {
            if($is_admin['data'])
            {
                $uid_arr = array();
                foreach ($is_admin['data'] as $row)
                {
                    $uid_arr[] = $row['id'];
                }
                $query->where('uid in ('.implode(',',$uid_arr).')');
            }
        }
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

        if ($params['domain_host'])
        {
            $domain_host_arr = explode('/shop/',trim(trim($params['domain_host']),'http://'));
            if ($domain_host_arr)
            {
                $domain = $domain_host_arr[0];
                $host = $domain_host_arr[1];
                $query->andFilterWhere(['like','domain',$domain])->andFilterWhere(['like','host',$host]);
            }
        }

        if ($params['uid'])
        {
            $query->andFilterWhere(['uid' => $params['uid']]);
        }

        if (!is_null($params['order_time_begin']))
        {
            $query->andFilterWhere(['>=', 'create_time', $params['order_time_begin']]);
        }

        if (!is_null($params['order_time_end']))
        {
            $query->andFilterWhere(['<=', 'create_time', $params['order_time_end']]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'sale_price' => $this->sale_price,
            'spu' => $this->spu,
            'price' => $this->price,
            'sale_end_hours' => $this->sale_end_hours,
            'ads_time' => $this->ads_time,
            'create_time' => $this->create_time,
            'uid' => $this->uid,
            'next_price' => $this->next_price,
            'designer' => $this->designer,
            'is_ads' => $this->is_ads,
            'ads_user' => $this->ads_user,
            'update_time' => $this->update_time,
            'is_group' => $this->is_group,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'info', $this->info])
//            ->andFilterWhere(['like', 'spu', $this->spu])
            ->andFilterWhere(['like', 'images', $this->images])
            ->andFilterWhere(['like', 'facebook', $this->facebook])
            ->andFilterWhere(['like', 'google', $this->google])
            ->andFilterWhere(['like', 'other', $this->other])
            ->andFilterWhere(['like', 'product_style_title', $this->product_style_title])
            ->andFilterWhere(['like', 'product_style', $this->product_style])
            ->andFilterWhere(['like', 'related_id', $this->related_id])
            ->andFilterWhere(['like', 'size', $this->size])
            ->andFilterWhere(['like', 'sale_city', $this->sale_city])
//            ->andFilterWhere(['like', 'domain', $this->domain])
//            ->andFilterWhere(['like', 'host', $this->host])
            ->andFilterWhere(['like', 'theme', $this->theme])
            ->andFilterWhere(['like', 'sale_info', $this->sale_info])
            ->andFilterWhere(['like', 'additional', $this->additional])
            ->andFilterWhere(['like', 'think', $this->think])
            ->andFilterWhere(['like', 'disable', $this->disable]);
        
        return $dataProvider;
    }
}
