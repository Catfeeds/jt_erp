<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>近期任务排期</title>

<style>
*{
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
}
body{
    font-family: Helvetica;
    -webkit-font-smoothing: antialiased;
    background: rgba( 71, 147, 227, 1);
}
h2{
    text-align: center;
    font-size: 18px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: red;
    padding: 30px 0;
}

/* Table Styles */

.table-wrapper{
    margin: 10px 70px 70px;
    box-shadow: 0px 35px 50px rgba( 0, 0, 0, 0.2 );
}

.fl-table {
    border-radius: 5px;
    font-size: 12px;
    font-weight: normal;
    border: none;
    border-collapse: collapse;
    width: 100%;
    max-width: 100%;
    white-space: nowrap;
    background-color: white;
}

.fl-table td, .fl-table th {
    text-align: center;
    padding: 8px;
}

.fl-table td {
    border-right: 1px solid #f8f8f8;
    font-size: 12px;
}

.fl-table thead th {
    color: #ffffff;
    background: #4FC3A1;
}


.fl-table thead th:nth-child(odd) {
    color: #ffffff;
    background: #324960;
}

.fl-table tr:nth-child(even) {
    background: #F8F8F8;
}

.fl-table tr td:nth-child(2) {
		text-align: left;
	}

/* Responsive */

@media (max-width: 767px) {
    .fl-table {
        display: block;
        width: 100%;
    }
    .table-wrapper:before{
        content: "Scroll horizontally >";
        display: block;
        text-align: right;
        font-size: 11px;
        color: white;
        padding: 0 0 10px;
    }
    .fl-table thead, .fl-table tbody, .fl-table thead th {
        display: block;
    }
    .fl-table thead th:last-child{
        border-bottom: none;
    }
    .fl-table thead {
        float: left;
    }
    .fl-table tbody {
        width: auto;
        position: relative;
        overflow-x: auto;
    }
    .fl-table td, .fl-table th {
        padding: 20px .625em .625em .625em;
        height: 60px;
        vertical-align: middle;
        box-sizing: border-box;
        overflow-x: hidden;
        overflow-y: auto;
        #width: 120px;
        font-size: 13px;
        text-overflow: ellipsis;
    }
    .fl-table thead th {
        text-align: left;
        border-bottom: 1px solid #f7f7f9;
    }
    .fl-table tbody tr {
        display: table-cell;
    }
    .fl-table tbody tr:nth-child(odd) {
        background: none;
    }
    .fl-table tr:nth-child(even) {
        background: transparent;
    }
    .fl-table tr td:nth-child(odd) {
        background: #F8F8F8;
        border-right: 1px solid #E6E4E4;
    }
    .fl-table tr td:nth-child(even) {
        border-right: 1px solid #E6E4E4;
    }
    .fl-table tbody td {
        display: block;
        text-align: center;
    }
	
}
</style>
</head>
<body>

<h2>IT近期任务排期 V1.0 更新：2018-10-31</h2>
<div class="table-wrapper">
    <table class="fl-table" id="fltable">
        <thead>
        <tr>
            <th>序号</th>
            <th>任务项</th>
            <th>排期规划</th>
            <th>交付日期</th>
            <th>开发工时</th>
            <th>进展</th>
            <th>责任人</th>
        </tr>
        </thead>
        <tbody>
        
        <tbody>
    </table>
</div>

<script>
var data = [[
    '1',
    '仓库：转寄匹配完成订单再取消后遗症解决',
    '11-02~11-05',
    '11-05',
    '4H',
    '进行中',
    '王勉'
],
[
    '1',
    '其他：重要域名备案1个，要把服务器放国内，要做备案',
    '11-07~11-09',
    '11-10',
    '2H',
    '未开始',
    '王勉'
],
[
    '1',
    '仓库：库存对应库位货架货号功能',
    '11-05~11-09',
    '11-12',
    '5D',
    '未开始',
    '王勉'
],
[
    '1',
    '运维：业务分离，商城和ERP代码服务器分离',
    '11-07~11-09',
    '11-12',
    '4H',
    '未开始',
    '王勉'
],
[
    '1',
    '仓库：同产品不同属性替换发货(库存或转寄仓库存)',
    '11-05~11-08',
    '11-12',
    '4D',
    '未开始',
    '邓小明'
],
[
    '1',
    '权限：库存修改权限收回管控',
    '11-02~11-02',
    '11-02',
    '1H',
    '完成已上线',
    '王勉'
],
[
    '1',
    '运维：git仓库转移，上线流程使用，开发服配置',
    '11-05~11-05',
    '11-05',
    '3H',
    '进行中',
    '王勉'
],
[
    '1',
    '仓库：库存抢占问题，脚本调整',
    '11-02~11-02',
    '11-02',
    '3H',
    '完成测试中',
    '王勉'
],
[
    '1',
    '其他：erp菜单任务表排期',
    '11-02~11-02',
    '11-02',
    '1H',
    '完成已上线',
    '王勉'
],
[
    '1',
    '采购：采购单收货逻辑修正',
    '10-27~10-29',
    '11-05',
    '8H',
    '完成待上线',
    '邓小明'
],
[
    '2',
    '采购：采购退货单单据开发',
    '10-29~10-31',
    '11-05',
    '3D',
    '完成测试中',
    '邓小明'
],
[
    '3',
    '其他：软件著作权文档编写，5份说明书，5份代码',
    '10-29~10-31',
    '10-31',
    '3D',
    '进行中 80%',
    '王勉'
],
[
    '3',
    '站点：站点信息加入投放状态字段',
    '10-31~10-31',
    '10-31',
    '2H',
    '完成已上线',
    '王勉'
],
[
    '3',
    '仓库：待采购已采购备货在途超时单订单匹配转寄库存导出',
    '10-31~10-31',
    '10-31',
    '2H',
    '完成已上线',
    '王勉'
],
[
    '3',
    '仓库：未出货订单匹配转寄仓同SPU统计结果导出，替换发货参考',
    '10-31~11-01',
    '11-01',
    '3H',
    '完成已上线',
    '王勉'
]];
var table=document.getElementById("fltable");console.log(table);
for(var i=0;i<data.length;i++){
    table.insertRow(i+1);
	table.rows[i+1].insertCell(0);
	table.rows[i+1].cells[0].innerText=i+1;
        for(j=1;j<7;j++){
            table.rows[i+1].insertCell(j);
            table.rows[i+1].cells[j].innerText=data[i][j];
        }
}

</script>

<div style="text-align:center;margin:50px 0; font:normal 14px/24px 'MicroSoft YaHei';">
<p>序号靠前的任务优先级越高，优先完成，对优先级有疑问请联系IT部，部分需求没有纳入排期表，请参考<a href='http://admin.kingdomskymall.net/demand/demand-pool?'>需求池</a></p>
<p>君天诺信版权所有</p>
</div>
</body>
</html>