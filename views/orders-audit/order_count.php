<?php

use yii\helpers\Html;

$this->title = 'Orders';
?>
<div class="orders-index box box-primary">
<?php echo $this->render('_table_search', ['orderTimeBegin' => $orderTimeBegin,'orderTimeEnd' => $orderTimeEnd,'country' => $country,'uid' => $uid,'groupMember' => $groupMember]); ?>
<div id="main" style="width: 1000px;height:618px;"></div>
<script src="/js/echarts.min.js"></script>
<script type="text/javascript">
var myChart = echarts.init(document.getElementById('main'));
var option = {
    title: {
        text: '销量'
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
        data:['销量']
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
            data : <?php
            $title = [];
            foreach ($res as $value) {
                $title[] = $value["c_date"];
            }
            echo(json_encode($title));
            ?>
        }
    ],
    yAxis : [
        {
            type : 'value'
        }
    ],
    series : [
        {
            name:'销售',
            type:'line',
            stack: '总量',
            label: {
                normal: {
                    show: true,
                    position: 'top'
                }
            },
            areaStyle: {normal: {}},
            data:<?php
            $values = [];
            foreach ($res as $value) {
                $values[] = $value["num"];
            }
            echo(json_encode($values));
            ?>
        }
    ]
};
myChart.setOption(option);
</script>
</div>