<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LocationLog */

$this->title = 'Update Location Log: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Location Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="location-log-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
