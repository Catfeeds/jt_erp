<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LocationLog */

$this->title = 'Create Location Log';
$this->params['breadcrumbs'][] = ['label' => 'Location Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-log-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
