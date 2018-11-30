<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Inventorys */

$this->title = '添加盘点单';
$this->params['breadcrumbs'][] = ['label' => '盘点单', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventorys-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
