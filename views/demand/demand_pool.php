<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>IT需求池</title>

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

.fl-table tr td:nth-child(3) {
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

<h2>IT需求池 V1.1 更新：2018-11-02</h2>
<div class="table-wrapper">
    <table id="fltable" class="fl-table">
        <thead>
        <tr>
            <th>序号</th>
            <th>模块</th>
            <th>需求描述</th>
            <th>预估工时</th>
            <th>状态</th>
        </tr>
        </thead>
        <tbody>
        
		
        <tbody>
    </table>
</div>
<script>
var data = [[
    '1',
    '采购',
    '采购退货单需求',
    '3D',
    '已完成'
],
[
    '2',
    '采购',
    '采购单收货功能',
    '1D',
    '已完成'
],
[
    '3',
    '其他',
    '软件著作权文档编写',
    '3D',
    '已完成'
],
[
    '4',
    '订单',
    '替换发货(库存货转寄仓库存)',
    '4D',
    '已排期'
],
[
    '4',
    '仓库',
    '库存对应库位号功能',
    '4D',
    '已排期'
],
[
    '5',
    '运维',
    'ERP和商城代码分离，数据库调整',
    '2D',
    '未排期'
],
[
    '5',
    '运维',
    'git代码转移到本地仓库，并规范上线流程',
    '2D',
    '未排期'
],
[
    '6',
    '运维',
    '数据日志备份，网站监控，告警',
    '2D',
    '未排期'
],
[
    '7',
    '采购',
    '采购价录入，售价控制，采购核算采销比',
    '3D',
    '未排期'
],
[
    '8',
    '仓库',
    '脚本顺序调整，解决抢占库存问题',
    '1D',
    '未排期'
],
[
    '8',
    '仓库',
    '物理调整单开发，调整盘点出来的差异或者其他原因差异',
    '3D',
    '未排期'
],
[
    '8',
    '权限',
    '离职人员权限管理及相关功能',
    '2D',
    '未排期'
],
[
    '8',
    '设置',
    '各国汇率自定义设置模块',
    '2D',
    '未排期'
],
[
    '8',
    '站点',
    '站点删除功能改成逻辑删除而非物理删除，后续可恢复',
    '4H',
    '未排期'
],
[
    '8',
    '站点',
    '新模板开发需求',
    '4D',
    '未排期'
],
[
    '8',
    '财务',
    '广告费导入到系统',
    '3D',
    '未排期'
],
[
    '8',
    '物流',
    '物流签收状态导入到系统',
    '3D',
    '未排期'
],
[
    '8',
    '仓库',
    '未送达订单中途退回仓库',
    '3D',
    '未排期'
],
[
    '8',
    '订单',
    '确认后订单取消功能',
    '3D',
    '未排期'
],
[
    '8',
    '订单',
    '客户黑名单列表及订单提醒',
    '2D',
    '未排期'
],
[
    '8',
    '仓库',
    '转寄仓面单打印报错问题处理',
    '2D',
    '未排期'
],
[
    '8',
    '站点',
    '下单页面产品推荐功能',
    '1D',
    '未排期'
],
[
    '8',
    '权限',
    '站点复制由权限组长分配',
    '1D',
    '未排期'
],
];
var table=document.getElementById("fltable");console.log(table);
for(var i=0;i<data.length;i++){
    table.insertRow(i+1);
	table.rows[i+1].insertCell(0);
	table.rows[i+1].cells[0].innerText=i+1;
        for(j=1;j<5;j++){
            table.rows[i+1].insertCell(j);
            table.rows[i+1].cells[j].innerText=data[i][j];
        }
}

</script>

<div style="text-align:center;margin:50px 0; font:normal 14px/24px 'MicroSoft YaHei';">
<p>需求池优先级部分先后，要查看已排期任务及优先级，请参考<a href='http://admin.kingdomskymall.net/demand/index?'>排期表</a></p>
<p>君天诺信版权所有</p>
</div>
</body>
</html>