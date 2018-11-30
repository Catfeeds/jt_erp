<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\OrdersItem */

$this->title = 'Create Orders Item';
$this->params['breadcrumbs'][] = ['label' => 'Orders Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-item-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
