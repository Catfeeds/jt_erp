<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SuppliersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '供应商';
$this->params['breadcrumbs'][] = $this->title;
if($is_select != 1) {
    $button_data = ['class' => 'yii\grid\ActionColumn','header'=>'','template' => ' {view} {update}{delete}'];
} else {
    $button_data = ['class' => 'yii\grid\ActionColumn','header'=>'','template' => ' {view}'];

}
?>
<div class="suppliers-index box box-primary">
    <?php if($is_select != 1) :?>

    <div class="box-header with-border">
        <?= Html::a('添加供应商', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php endif;?>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'name',
                'area',
                'address',
                'contacts',
                // 'phone',
                // 'url:url',
                // 'status',
                // 'uid',
                // 'create_time',

                $button_data,
            ],
        ]); ?>
    </div>
</div>
