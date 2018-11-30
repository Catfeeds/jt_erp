<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LocationStock */

$this->title = 'Create Location Stock';
$this->params['breadcrumbs'][] = ['label' => 'Location Stocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-stock-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
