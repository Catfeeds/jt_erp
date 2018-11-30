<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Warehouse */

$this->title = 'Create Warehouse';
$this->params['breadcrumbs'][] = ['label' => 'Warehouses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="warehouse-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
