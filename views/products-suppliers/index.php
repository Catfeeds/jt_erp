<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSuppliersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products Suppliers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-suppliers-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Create Products Suppliers', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'supplier_id',
                'sku',
                'url:ntext',
                'min_buy',
                // 'price',
                // 'deliver_time:datetime',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
