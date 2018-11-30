<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ProductsBase */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Products Bases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-base-view box box-primary">
    <?php if($is_select != 1) :?>
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>

    </div>
    <?php endif;?>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'title',
                'en_name',
                'categorie'=>['label'=>'产品分类','value'=>$model->categories_info->cn_name],
                        'product_type'=>[
                    'attribute' => 'product_type',
                    'value' => $model->product_type == 1 ? '普货' : '特货'
                ],
                'sex'=>[
                    'attribute' => 'sex',
                    'value' => $model->sex == 0 ? '通用' : $model->sex == 1 ? '男' : '女'
                ],
                'spu',
                'image'=>['label' => '标签图',
                    'format' => 'raw',
                    'value' => Html::a(Html::img($model->image, ['width' => 200]),$model->image),
                    ],
                'uid'=>['label'=>'产品开发人员','value'=>$model->user_info->username],
                'open' =>[
                    'attribute' => 'open',
                    'value' =>  $model->open == 0 ? '未选择' : $model->open == 1 ? '组内可见' : '所有人可见',
                ],

                'declaration_hs',
                'create_time',
            ],
        ]) ?>
    </div>
</div>
