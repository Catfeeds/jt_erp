<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProductComment */

$this->title = '修改产品评论: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '产品评论', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-comment-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
