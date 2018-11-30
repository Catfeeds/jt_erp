<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AdFeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ad Fees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-fee-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Create Ad Fee', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('Import', ['import'], ['class' => 'btn btn-success btn-flat']) ?>
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
                'website_id',
                'ad_total',
                'ad_date',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
