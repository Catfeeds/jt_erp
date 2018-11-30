<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Websites */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Websites', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="websites-view box box-primary">
    <?php if($is_select != 1) :?>
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('更新SKU', ['/websites-sku/index', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
    </div>
    <?php endif;?>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'title',
                'sale_price',
                'price',
                'sale_end_hours',
                'info:html',
                [
                        'attribute' => 'images',
                        'format' => 'raw',
                        'value' => function($data){
                            $images = json_decode($data->images);
                            $html = '';
                            foreach($images as $v){
                                $html .= '<img src="'.$v.'" width="100">';
                            }
                            return $html;

                        }
                ],
                'facebook:ntext',
                'google:ntext',
                'other:ntext',
                'product_style_title',
                'product_style:html',
                'related_id',
                'size',
                'sale_city',
                'domain',
                'host',
                'theme',
                'ads_time',
                'create_time',
                'uid',
                'sale_info',
                'additional:html',
                'next_price',
                'designer',
                'is_ads',
                'ads_user',
                'think:ntext',
                'update_time',
                'disable',
                'cloak',
                'is_group',
            ],
        ]) ?>
    </div>
</div>
