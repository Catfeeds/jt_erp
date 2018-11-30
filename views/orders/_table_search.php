<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
?>
<div class="orders-search">
<?php
if (empty($formAction))
{
    $formAction = '/orders/order-count';
}
echo Html::beginForm('','get',['id'=>'form','class'=>'form','data'=>'myself']);
?>
<?php
    echo DateTimePicker::widget([ 
        'name' => 'order_time_begin', 
        'options' => ['placeholder' => '下单范围查询-开始', 'style' => 'width:200px;'], 
        //注意，该方法更新的时候你需要指定value值 
        'value' => $orderTimeBegin, 
        'pluginOptions' => [
            'autoclose' => true, 
            'format' => 'yyyy-mm-dd', 
            'todayHighlight' => true,
            'startView'=>2,    //其实范围（0：日  1：天 2：年）
            'minView'=>2,  //最大选择范围（年）
            'maxView'=>2,  //最小选择范围（年）
        ]
    ]);
    ?>
    <?php
    echo DateTimePicker::widget([ 
        'name' => 'order_time_end', 
        'options' => ['placeholder' => '下单范围查询-结束', 'style' => 'width:200px;'], 
        //注意，该方法更新的时候你需要指定value值 
        'value' => $orderTimeEnd, 
        'pluginOptions' => [
            'autoclose' => true, 
            'format' => 'yyyy-mm-dd', 
            'todayHighlight' => true,
            'startView'=>2,    //其实范围（0：日  1：天 2：年）
            'minView'=>2,  //最大选择范围（年）
            'maxView'=>2,  //最小选择范围（年）
        ]
    ]);
    ?>
    <?php
    echo Html::dropDownList('country', $country, [0 => "请选择国家"] + \app\models\Websites::$country_array,['class'=>'form-control']);

    $group = [0 => "请选择销售人员"];
    foreach ($groupMember as $value)
    {
        $group[$value['user_id']] = $value['name'];

        if (empty($value['name']))
        {
            $group[$value['user_id']] = $value['username'];
        }
    }
    echo Html::dropDownList('uid', $uid, $group,['class'=>'form-control']);
    ?>
  
    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
    </div>

    <?= Html::endForm(); ?>
    </div>