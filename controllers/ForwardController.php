<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/8 0008
 * Time: 15:19
 */

namespace app\controllers;

use app\models\Forward;
use app\models\OrderRecord;
use app\models\Orders;
use Yii;
use app\models\ForwardSearch;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class ForwardController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Orders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ForwardSearch();

        $param = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($param);
        //直接修改列表中的数据

        $forwardTimeBegin = "";
        $forwardTimeEnd = "";
        $addTimeBegin = "";
        $addTimeEnd = "";

        if (!empty(Yii::$app->request->queryParams["forward_time_begin"]))
        {
            $forwardTimeBegin = Yii::$app->request->queryParams["forward_time_begin"];
        }

        if (!empty(Yii::$app->request->queryParams["forward_time_end"]))
        {
            $forwardTimeBegin = Yii::$app->request->queryParams["forward_time_end"];
        }

        if (!empty(Yii::$app->request->queryParams["add_time_begin"]))
        {
            $addTimeBegin = Yii::$app->request->queryParams["add_time_begin"];
        }

        if (!empty(Yii::$app->request->queryParams["add_time_end"]))
        {
            $addTimeBegin = Yii::$app->request->queryParams["add_time_end"];
        }

        return $this->render('index', [
            'forward_time_begin' => $forwardTimeBegin,
            'add_time_begin' => $addTimeBegin,
            'forward_time_end' => $forwardTimeEnd,
            'add_time_end' => $addTimeEnd,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //订单批量导入转寄仓
    public function actionImportForward()
    {
        $connection = Yii::$app->db;
        //返回值标注
        $info = array(
            'error' => array(),
            'warning' => array(),
            'success' => array()
        );
        $total = $success = 0;
        $data = Yii::$app->request->post();
        if ($data['data'])
        {
            $count = 1;
            $data_arr = $this->getDataRow($data['data']);
            $stock_code = $data['stock_code'];  //转寄仓
            if ($data_arr)
            {
                foreach ($data_arr as $row)
                {
                    ++$total;
                    if (empty($row))
                    {
                        $info['error'][] = sprintf('第%s行:请输入订单号！', $count++);
                        continue;
                    }
                    $row = explode("\t", $row,1);
                    $id_order = trim($row[0]);
                    if (!$id_order)
                    {
                        $info['error'][] = sprintf('第%s行: 订单号不能为空:%s！', $count++,trim($row[0]));
                        continue;
                    }
                    //获取订单信息
                    $order_info = Orders::find()->where(array('id'=>trim($row[0])))->one();
                    if (empty($order_info))
                    {
                        $info['error'][] = sprintf('第%s行: 订单ID:%s,订单信息为空！', $count++,$row[0]);
                        continue;
                    }
                    if (!in_array($order_info['status'],array(4,6)))
                    {
                        $info['error'][] = sprintf('第%s行: 订单ID:%s,订单状态不为已发货或拒签状态！', $count++,$row[0]);
                        continue;
                    }
                    //添加转寄仓订单记录
                    $tr = ActiveRecord::getDb()->beginTransaction();
                    $res = Forward::addForward($id_order,$stock_code,$order_info['country'],$order_info['lc_number']);
                    $order_info->status = 14;   //转寄仓未匹配
                    $res_two = $order_info->save();
                    if (!$res || !$res_two || !OrderRecord::addRecord($id_order,14,4,'订单导入转寄仓,订单状态为转寄仓未匹配'))
                    {
                        $tr->rollBack();
                        $info['error'][] = sprintf('第%s行: 订单ID:%s,导入失败！', $count++,$id_order);
                        continue;
                    }
                    $tr->commit();
                    $success++;
                }
            }
        }
        return $this->render("import_forward",[
            'info'=>$info,
            'total'=>$total,
            'success'=>$success,
            'data'=>$data['data'],
        ]);
    }

    //订单批量导入转寄仓
    public function actionRelieveForward()
    {
        $connection = Yii::$app->db;
        //返回值标注
        $info = array(
            'error' => array(),
            'warning' => array(),
            'success' => array()
        );
        $total = $success = 0;
        $data = Yii::$app->request->post();
        if ($data['data'])
        {
            $count = 1;
            $data_arr = $this->getDataRow($data['data']);
            if ($data_arr)
            {
                foreach ($data_arr as $row)
                {
                    ++$total;
                    if (empty($row))
                    {
                        $info['error'][] = sprintf('第%s行:请输入订单号！', $count++);
                        continue;
                    }
                    $row = explode("\t", $row,1);
                    $id_order = trim($row[0]);
                    if (!$id_order)
                    {
                        $info['error'][] = sprintf('第%s行: 订单号不能为空:%s！', $count++,trim($row[0]));
                        continue;
                    }
                    //获取订单信息
                    $order_info = Orders::find()->where(array('id'=>trim($row[0])))->one();
                    if (empty($order_info))
                    {
                        $info['error'][] = sprintf('第%s行: 订单ID:%s,订单信息为空！', $count++,$row[0]);
                        continue;
                    }
                    if (!in_array($order_info['status'],array(13)))
                    {
                        $info['error'][] = sprintf('第%s行: 订单ID:%s,订单状态不为待转运状态！', $count++,$row[0]);
                        continue;
                    }
                    //获取转运仓对应的订单信息
                    $forward_info = Forward::find()->where(array('new_id_order'=> $id_order))->one();
                    if (!$forward_info)
                    {
                        $info['error'][] = sprintf('第%s行: 订单ID:%s,转寄仓未找到对应订单，请咨询技术！', $count++,$row[0]);
                        continue;
                    }
                    //添加转寄仓订单记录
                    $forward_id_order = $forward_info['id_order'];
                    $tr = ActiveRecord::getDb()->beginTransaction();
                    $forward_info->new_id_order = 0;
                    $forward_info->status = 0;
                    $forward_info->new_lc_number = '';
                    $forward_info->forward_time = '';
                    $res = $forward_info->save();

                    $order_info->status = 2;   //已确认
                    $res_two = $order_info->save();
                    $res_three = OrderRecord::addRecord($id_order,2,4,'解除待转运');

                    $res_four = Yii::$app->db->createCommand("update orders set status = 14 where id = ".$forward_id_order)->execute();
                    $res_five = OrderRecord::addRecord($forward_id_order,14,4,'转寄仓关系解除，订单状态更新为转寄仓未匹配');

                    if (!$res || !$res_two || !$res_three || !$res_four || !$res_five)
                    {
                        $tr->rollBack();
                        $info['error'][] = sprintf('第%s行: 订单ID:%s,导入失败,请稍后重试！', $count++,$id_order);
                        continue;
                    }
                    $tr->commit();
                    $success++;
                }
            }
        }
        return $this->render("relieve_forward",[
            'info'=>$info,
            'total'=>$total,
            'success'=>$success,
            'data'=>$data['data'],
        ]);
    }

    /**
     * @param $data
     * @return array
     */
    public function getDataRow($data)
    {
        if (empty($data))
            return array();
        $data = preg_split("~[\r\n]~", $data, -1, PREG_SPLIT_NO_EMPTY);
        return $data;
    }

    //获取待采购，已采购，备货在途，超时单能匹配到的转寄订单数据
    public function actionGetForward()
    {
        //获取待采购，已采购，备货在途，超时单的订单
        $sql = "select id from orders where status in (3,20,21,12)";
        $id_arr = Yii::$app->db->createCommand($sql)->queryAll();
        $info_array = array();
        if($id_arr)
        {
            foreach ($id_arr as $id)
            {
                $res_forward = $this->orderForwardWarehouse($id['id']);
                if ($res_forward)
                {
                    $order_str = implode('—',array_values($res_forward));
                    $info_array[$id['id']]['str'] = $order_str;
                    $info_array[$id['id']]['count'] = count($res_forward);
                }
            }
            $column = "订单号,国家,订单状态,sku->qty,匹配转寄仓订单数量,匹配转寄仓订单\n";
            if ($info_array)
            {
                foreach ($info_array as $id_order => $value)
                {
                    $sql = "select a.order_id,b.country,b.status,GROUP_CONCAT(CONCAT(a.sku,'->',a.qty) Separator '; ') as sku_qty from orders_item a join orders b on a.order_id = b.id where a.order_id = ".$id_order;
                    $info = Yii::$app->getDb()->createCommand($sql)->queryAll();
                    if ($info)
                    {
                        $column.= $info[0]['order_id'].",".
                            $info[0]['country'].",".
                            $info[0]['status'].",".
                            $info[0]['sku_qty'].",".
                            $value['count'].",".
                            $value['str']."\n";
                    }
                }
            }
            $filename = date('匹配转寄仓数据_Y_m_d') . '.csv'; //设置文件名
            $this->export_csv($filename, iconv("UTF-8","GBK//IGNORE",$column)); //导出
            exit;
        }

    }

    public function orderForwardWarehouse($id_order)
    {
        //获取订单信息
        $order_info = Orders::findOne($id_order);
        if (!isset($order_info['status']) || !in_array($order_info['status'],array(3,20,21,12)))
        {
            return false;
        }
        $country = $order_info['country'];
        //获取国家转寄仓库
        $sql = "select stock_code from warehouse where stock_type = 2 and stock_code like '".$country."%'";
        $warehouse_arr = Yii::$app->db->createCommand($sql)->queryAll();
        if ($warehouse_arr)
        {
            foreach ($warehouse_arr as $stock_code)
            {
                $res = $this->match_forward_order($id_order,$stock_code['stock_code']);
                if (isset($res['flag']) && $res['flag'])
                {
                    //匹配到转寄仓
                   return $res['data']; //获取到匹配到的转寄仓订单id
                }
            }
        }
        return false;
    }

    //匹配转寄仓
    public function match_forward_order($id_order, $stock_code)
    {
        $flag = 0;
        $forward_model = new Forward();
        $order_item = $forward_model->order_integration($id_order);

        $count = count($order_item);
        //判断该订单产品与转寄仓产品是否完全一致
        $order_forward_id = array();
        foreach ($order_item as $sku => $val)
        {
            $forward_where = array();
            $forward_where['status'] = 0; //未匹配
            $stock_code && $forward_where['stock_code'] = $stock_code;
            $sql = "select a.id_order,b.sku,b.qty from forward a join orders_item b on a.id_order = b.order_id where a.status = 0 and a.stock_code = '" . $stock_code . "' and b.sku = '".$sku."' order by a.add_time desc" ;
            $forward_arr = Yii::$app->db->createCommand($sql)->queryAll();  //获取转寄仓相同sku的订单
            if ($forward_arr)
            {
                $forward = array();
                foreach ($forward_arr as $v)
                {
                    @$forward[$v['sku'] . '-' . $v['id_order']]['total'] = $forward[$v['sku'] . '-' . $v['id_order']]['total'] + $v['qty'];
                    $forward[$v['sku'] . '-' . $v['id_order']]['id_order'] = $v['id_order'];
                }
                foreach ($forward as $v)
                {
                    if ($val['qty'] == $v['total'])
                    {
                        $order_forward_id[] = $v['id_order'];
                    }
                }
            }
            else
            {
                return array('flag' => $flag, 'mes' => '未在转寄仓中找到订单！');
            }
        }
        //查找id_order的重复数
        $result = array();
        foreach ($order_forward_id as $id_order)
        {
            @$result[$id_order] = $result[$id_order] + 1;
        }
        //进行具体匹配
        $return = array();
        foreach ( $result as $id_order => $val)
        {
            if ( $val == $count )
            {
                $sql = 'select sku from orders_item where order_id = '.$id_order.' and qty != 0 group by sku;';
                $sku_arr = Yii::$app->db->createCommand($sql)->queryAll();
                //匹配转寄仓订单产品数与所配订单是否一样
                if ( count($sku_arr) == $count )
                {
                    $return[] = $id_order;
                }
            }
        }
        if ($return)
        {
            return array('flag' => 1, 'data' => $return);
        }
        else
        {
            return array('flag' => $flag, 'mes' => '未在转寄仓中找到订单！');
        }
    }

    /**
     * 模板
     */
    protected function export_csv($filename, $data) {
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=" . $filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $data;
    }

    public function actionGetForwardAbnormal()
    {
        //获取待采购，已采购，备货在途，超时单的订单
        $sql = "select id from orders where status in (16)";
        $id_arr = Yii::$app->db->createCommand($sql)->queryAll();
        $info_array = array();
        if($id_arr)
        {
            foreach ($id_arr as $id)
            {
                $res_forward = Forward::order_forward_warehouse_abnormal($id['id']);
                if ($res_forward)
                {
                    $order_str = implode('—',array_values($res_forward));
                    $info_array[$id['id']]['str'] = $order_str;
                    $info_array[$id['id']]['count'] = count($res_forward);
                }
            }
            $column = "订单号,国家,订单状态,sku->qty,匹配转寄仓订单数量,匹配转寄仓订单\n";

            if ($info_array)
            {
                foreach ($info_array as $id_order => $value)
                {
                    $sql = "select a.order_id,b.country,b.status,GROUP_CONCAT(CONCAT(a.sku,'->',a.qty) Separator '; ') as sku_qty from orders_item a join orders b on a.order_id = b.id where a.order_id = ".$id_order;
                    $info = Yii::$app->getDb()->createCommand($sql)->queryAll();
                    if ($info)
                    {
                        $column.= $info[0]['order_id'].",".
                            $info[0]['country'].",".
                            $info[0]['status'].",".
                            $info[0]['sku_qty'].",".
                            $value['count'].",".
                            $value['str']."\n";
                    }
                }
            }
            $filename = date('匹配转寄仓数据_Y_m_d') . '.csv'; //设置文件名
            $this->export_csv($filename, iconv("UTF-8","GBK//IGNORE",$column)); //导出
            exit;
        }

    }


}
