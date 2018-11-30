<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Purchases */

$this->title = 'Create Purchases';
$this->params['breadcrumbs'][] = ['label' => 'Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchases-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
