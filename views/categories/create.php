<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Categories */

$this->title = '添加分类';
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-create">

    <?= $this->render('_form', [
    'model' => $model,
    'categories' => $categories,
    ]) ?>

</div>
