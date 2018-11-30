<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WebsitesBaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $orderTimeBegin yii\data\ActiveDataProvider */
/* @var $orderTimeEnd yii\data\ActiveDataProvider */
/* @var $domain_host yii\data\ActiveDataProvider */
/* @var $user_arr yii\data\ActiveDataProvider */
/* @var $uid yii\data\ActiveDataProvider */

$this->title = '站点管理';
$this->params['breadcrumbs'][] = $this->title;
if($is_select != 1) {
    $button_data = ['class' => 'yii\grid\ActionColumn','header'=>'','template' => ' {view} {update}'];
    $copy = [
        'label' => '复制',
        'value' => function($data){
            return Html::a('复制', Yii::$app->urlManager->createUrl(['/websites/copy', 'id' => $data->id]));
        },
        'format' => 'raw',
    ];
} else {
    $copy = [
        'label' => '复制',
        'value' => function($data){
            return '';
        },
        'format' => 'raw',
    ];
    $button_data = ['class' => 'yii\grid\ActionColumn','header'=>'','template' => ' {view}'];

}
?>
<div class="websites-index box box-primary">
    <?php if($is_select != 1) :?>
    <div class="box-header with-border">

    </div>
    <?php endif;?>
    <div class="box-body table-responsive">
        <?php echo $this->render('_search', ['model'=>$searchModel,'orderTimeBegin'=>$orderTimeBegin,'orderTimeEnd'=>$orderTimeEnd,'domain_host'=>$domain_host,'user_arr'=>$user_arr,'uid'=>$uid]); ?>

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                [
                        'attribute' => 'host',
                        'label' => '站点地址',
                        'format' => 'raw',
                        'value' => function($data){
                            return Html::a('http://'.$data->domain.'/shop/'.$data->host, 'http://'.$data->domain.'/shop/'.$data->host, ['target' => '_blank']);
                        }
                ],
                'spu',
                [
                    'label' => '产品图片',
                    'format' => 'raw',
                    'value' => function($data){
                        $items = $data->getSpuImages($data->spu);
                        $html = '<img src="' . $items->image . '" width="50">';
                        //$images = json_decode($data->images);
                        //$html = '';
                        //foreach($images as $v){
                          //  $html .= '<img src="'.$v.'" width="100">';
                        //}

                        return $html;
                    }
                ],
                [
                    'label' => '产品开发人',
                    'format' => 'raw',
                    'value' => function($data){
                        $items = $data->getUsers($data->uid);
                        $html = $items->username;
                        //$images = json_decode($data->images);
                        //$html = '';
                        //foreach($images as $v){
                        //  $html .= '<img src="'.$v.'" width="100">';
                        //}

                        return $html;
                    }
                ],
                'title',
                'sale_price',
                'price',
                'sale_end_hours',
                // 'info:ntext',
                // 'images:ntext',
                // 'facebook:ntext',
                // 'google:ntext',
                // 'other:ntext',
                // 'product_style_title',
                // 'product_style:ntext',
                // 'related_id',
                // 'size',
                // 'sale_city',
                // 'domain',
                // 'host',
                // 'theme',
                // 'ads_time',
                 'create_time',
                // 'uid',
                // 'sale_info',
                // 'additional:ntext',
                // 'next_price',
                // 'designer',
                // 'is_ads',
                // 'ads_user',
                // 'think:ntext',
                // 'update_time',
                // 'disable',
                // 'is_group',
                ['class' => 'yii\grid\ActionColumn','header'=>'评论管理','template' => '{add}',
                    'buttons' => [
                        'add' => function ($url, $model, $key) {
                            return  Html::a('<span class="btn btn-info	">评论管理</span>', '/product-comment/index?website_id='.$model->id) ;
                        },
                    ],
                    'headerOptions' => ['width' => '180']
                ],

                $button_data,

                $copy

            ],
        ]); ?>
    </div>
</div>
