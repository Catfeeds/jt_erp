<?php

use yii\helpers\Html;

$this->title = 'Orders';
?>
<div class="orders-index box box-primary">
<?php echo $this->render('_table_search', ['formAction' => '/orders/money-count', 'orderTimeBegin' => $orderTimeBegin,'orderTimeEnd' => $orderTimeEnd,'country' => $country,'uid' => $uid,'groupMember' => $groupMember]); ?>
<div id="total" style="width: 1000px;font-size:36px;">
    销售总额：<?= $moneyTotal ?>
</div>
<div id="main" style="width: 1000px;height:618px;"></div>
<script src="/js/echarts.min.js"></script>
<script type="text/javascript">
var myChart = echarts.init(document.getElementById('main'));
var option = {
    title: {
        text: '销售额'
    },
    tooltip : {
        trigger: 'axis',
        axisPointer: {
            type: 'cross',
            label: {
                backgroundColor: '#6a7985'
            }
        }
    },
    legend: {
        data:<?php
        $values = [];
        foreach ($res as $user) {
            $values[] = $user['name'];
        }
        echo(json_encode($values));
        ?>
    },
    toolbox: {
        feature: {
            saveAsImage: {}
        }
    },
    grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true
    },
    xAxis : [
        {
            type : 'category',
            boundaryGap : false,
            data : <?php echo(json_encode($dates)); ?>
        }
    ],
    yAxis : [
        {
            type : 'value'
        }
    ],
    series : [
        <?php
        foreach ($res as $user) {
        ?>
        {
            name:'<?php echo($user['name']); ?>',
            type:'line',
            stack: '<?php echo($user['name']); ?>',
            areaStyle: {normal: {}},
            data:<?php echo(json_encode(array_values($user['value'])));
            ?>
        },
        <?php
        }
        ?>
    ]
};
myChart.setOption(option);
</script>
</div>