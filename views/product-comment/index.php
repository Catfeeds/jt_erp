<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductCommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '产品评论';
$this->params['breadcrumbs'][] = $this->title;
if($is_select != 1) {
    $button_data = ['class' => 'yii\grid\ActionColumn','header'=>'','template' => ' {view} {update}{delete}'];
} else {
    $button_data = ['class' => 'yii\grid\ActionColumn','header'=>'','template' => ' {view}'];

}
?>
<div class="product-comment-index box box-primary">
    <?php if($is_select != 1) :?>
    <div class="box-header with-border">
        <?= Html::a('创建评论', ['create?website_id=' . $websiteId], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php endif;?>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'website_id',
                'name',
                'phone',
                'body:html',
                // 'ip',
                // 'isshow',
                // 'add_time',

                $button_data,
            ],
        ]); ?>
    </div>
</div>
