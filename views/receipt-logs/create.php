<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ReceiptLogs */

$this->title = 'Create Receipt Logs';
$this->params['breadcrumbs'][] = ['label' => 'Receipt Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receipt-logs-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
