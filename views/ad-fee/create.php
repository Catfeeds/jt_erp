<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AdFee */

$this->title = 'Create Ad Fee';
$this->params['breadcrumbs'][] = ['label' => 'Ad Fees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-fee-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
