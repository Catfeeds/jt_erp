<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Suppliers */

$this->title = '添加供应商';
$this->params['breadcrumbs'][] = ['label' => 'Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="suppliers-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
