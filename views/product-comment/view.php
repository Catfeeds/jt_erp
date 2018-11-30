<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ProductComment */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '产品评论', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-comment-view box box-primary">
    <?php if($is_select != 1) :?>
    <div class="box-header">
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => '你确定删除这条数据吗?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <?php endif;?>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'website_id',
                'name',
                'phone',
                'body:html',
                'ip',
                'isshow',
                'add_time',
            ],
        ]) ?>
    </div>
</div>
