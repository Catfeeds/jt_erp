<?php

use yii\helpers\Html;
use yii\grid\GridView;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel mdm\admin\models\searchs\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rbac-admin', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?php
        echo Html::a(Yii::t('rbac-admin', 'Create'), ['signup'], [
            'class' => 'btn btn-danger'
        ]);
        ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'username',
            'name',
            'leader',
            'email:email',
            'created_at:date',
            // [
            //     'attribute' => 'status',
            //     'value' => function($model) {
            //         return $model->status == 0 ? 'Inactive' : 'Active';
            //     },
            //     'filter' => [
            //         0 => 'Inactive',
            //         10 => 'Active'
            //     ]
            // ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model) {
                    $status = $model->status;
                    $select = "<select name='select_status".$model->id."'  onchange='changeStatus(" . $model->id . ")'>";
                    $status_arr = array('0'=>'Inactive','10'=>'Active');
                    foreach ($status_arr as $k =>$v)
                    {
                        if ($status == $k)
                        {
                            $selected = "selected";
                        }
                        else
                        {
                            $selected = "";
                        }
                        $select .= "<option value='$k' $selected>$v</option>";
                    }
                    $select .= "</select>";
                    return $select;
                },
                'filter' => [
                    0 => 'Inactive',
                    10 => 'Active'
                ]
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => Helper::filterActionColumn(['view', 'activate', 'delete']),
                'buttons' => [
                    'activate' => function($url, $model) {
                        if ($model->status == 10) {
                            return '';
                        }
                        $options = [
                            'title' => Yii::t('rbac-admin', 'Activate'),
                            'aria-label' => Yii::t('rbac-admin', 'Activate'),
                            'data-confirm' => Yii::t('rbac-admin', 'Are you sure you want to activate this user?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, $options);
                    }
                    ]
                ],
            ],
        ]);
        ?>
</div>
<script>
    function changeStatus( id) {
        var status_value =  $("select[name='select_status"+id+"']").val();
        console.log(id);
        console.log(status_value);
        $.ajax({
            url:"/admin/user/change-status",
            type:'POST',
            dataType:'json',
            data:{
                'id': id,
                'status': status_value
            },
            success:function(data){
                console.log(data);
                alert(data.msg);
            }
        });
    }
</script>
