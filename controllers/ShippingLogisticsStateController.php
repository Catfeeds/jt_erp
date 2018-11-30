<?php

namespace app\controllers;

use app\models\ShippingLogisticsState;
use app\models\ShippingLogisticsStateSearch;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ShippingLogisticsStateController implements the CRUD actions for ShippingLogisticsState model.
 */
class ShippingLogisticsStateController extends Controller
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
     * Lists all ShippingLogisticsState models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShippingLogisticsStateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionImportAll()
    {
        $state_arr = array_flip(ShippingLogisticsState::$state_arr);
        $notice = array();
        if (Yii::$app->request->isPost)
        {
            $file = UploadedFile::getInstanceByName('shippingLogisticsStateData');

            if (strpos($file->name, ".xlsx") > 0)
            {
                $PHPReader = new \PHPExcel_Reader_Excel2007();
                $PHPExcel = $PHPReader->load($file->tempName);
                $currentSheet = $PHPExcel->getSheet(0);
                $allRow = $currentSheet->getHighestRow();

                for ($currentRow = 2; $currentRow <= $allRow; $currentRow++)
                {
                    $record = [];
                    for ($column = 'A'; $column <= 'C'; $column++)
                    {
                        $data = trim($currentSheet->getCell($column . $currentRow)->getValue());
                        array_push($record, $data);
                    }

                    if (!trim($record[0]) &&  !trim($record[1]) && !trim($record[2]))
                    {
                        continue;
                    }
                    //获取订单物流信息及进行数据验证
                    if (!trim($record[2]) || !isset($state_arr[trim($record[2])]))
                    {
                        array_push($notice, $record[0] . " <font color='#ff0000'>物流状态对应不正确，请确认后再操作</font><br>");
                        continue;
                    }
                    $state = $state_arr[trim($record[2])];  //物流状态
                    if (!empty(trim($record[0])) && !empty(trim($record[1])))
                    {
                        $id_order = trim($record[0]);
                        $lc_number = trim($record[1]);
                        $return = ShippingLogisticsState::save_shipping_state($id_order,$lc_number,$state);
                        if (isset($return['status']) && !$return['status'])
                        {
                            array_push($notice, $id_order . " <font color='#ff0000'>".$return['msg']."</font><br>");
                            continue;
                        }
                    }
                    else
                    {
                        array_push($notice, trim($record[0])."&".trim($record[1]) . " <font color='#ff0000'>订单号或者运单号为空</font><br>");
                        continue;
                    }
                }
            }
            else
            {
                $notice[] = '文件格式错误，请上传xlsx格式文件';
            }
        }
        return $this->render("import_all", ["notice" => $notice]);
    }

}
