<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AdFee */

$this->title = 'Update Ad Fee: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ad Fees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ad-fee-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
