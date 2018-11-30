<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Back */

$this->title = '新建退货单';
$this->params['breadcrumbs'][] = ['label' => 'Backs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="back-create">

    <?= $this->render('_form', [
        'model' => $model,
        'back' => $back,
    ]) ?>

</div>
