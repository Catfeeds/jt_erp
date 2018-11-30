<?php

namespace app\controllers;

use Yii;
use app\models\Stocks;
use app\models\StocksSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * StocksController implements the CRUD actions for Stocks model.
 */
class StocksController extends Controller
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
     * Lists all Stocks models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StocksSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Stocks model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Stocks model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Stocks();


        if ($model->load(Yii::$app->request->post())) {
            $model->uid = Yii::$app->user->id;
            $model->create_date = date('Y-m-d H:i:s');
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Stocks model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Stocks model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Stocks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Stocks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Stocks::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionImportStocks()
    {

        if (Yii::$app->request->isPost) {
            $objectPHPExcel = new \PHPExcel();
            $file = UploadedFile::getInstanceByName('stocksData');
            if (strpos($file->name, ".xlsx") > 0) {
                $PHPReader = new \PHPExcel_Reader_Excel2007();
                $PHPExcel = $PHPReader->load($file->tempName);
                $currentSheet = $PHPExcel->getSheet(0);
                $allRow = $currentSheet->getHighestRow();
                $modifyArr = [];
                for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
                    $record = [];
                    for ($column = 'A'; $column <= 'F'; $column++) {
                        $data = $currentSheet->getCell($column . $currentRow)->getValue();
                        array_push($record, $data);
                        // $val = $currentSheet->getCellByColumnAndRow($column, $currentRow)->getValue();

                    }

                    if (!empty($record[0]) && !empty($record[1])) {
                        // 取当前库存
                        $Stocks = new Stocks();
                        $stocks_info = $Stocks->find()->where(['stock_code' => $record[0], 'sku' => $record[1]])->asArray()->one();
                        if ($stocks_info) {
                            $stock = $stocks_info['stock'] + $record[2];
                            $UpdateStock = Stocks::findOne(['stock_code' => $record[0], 'sku' => $record[1]]);
                            $UpdateStock->stock = $stock;
                            if ($record['3']) {
                                $UpdateStock->cost = $record['3'];

                            }
                            $UpdateStock->uid = Yii::$app->user->getId();

                            $UpdateStock->update_date = date('Y-m-d H:i:s');
                            if ($UpdateStock->save()) {

//                                $StockLogs = new StockLogs();
//                                $StockLogs->sku = $record[1];
//                                $StockLogs->qty = $record[2];
//                                $StockLogs->cost = $record[3];
//                                $StockLogs->uid = Yii::$app->user->getId();
//                                $StockLogs->log_type = $record[3]>=0?3:4;
//                                $StockLogs->create_date = date('Y-m-d H:i:s');
//                                $StockLogs->save();
                                array_push($modifyArr, '仓库编号' . $record[0] . ' sku' . $record[1] . " <font color='#00ff00'>库存更新成功</font><br>");
                            } else {
                                array_push($modifyArr, '仓库编号' . $record[0] . ' sku' . $record[1] . " <font color='#ff0000'>库存更新失败</font><br>");
                            }
                        } else {
                            $insertStocks = new Stocks();
                            $insertStocks->stock_code = $record[0];
                            $insertStocks->sku = $record[1];
                            $insertStocks->stock = $record[2];
                            $insertStocks->cost = $record[3] ? $record[3] : 0;
                            $insertStocks->uid = Yii::$app->user->getId();
                            $insertStocks->create_date = date('Y-m-d H:i:s');
                            $insertStocks->update_date = date('Y-m-d H:i:s');
                            if ($insertStocks->save()) {
//                                $StockLogs = new StockLogs();
//                                $StockLogs->sku = $record[1];
//                                $StockLogs->qty = $record[2];
//                                $StockLogs->cost = $record[3]?$record[3]:0;
//                                $StockLogs->uid = Yii::$app->user->getId();
//                                $StockLogs->log_type = $record[3]>=0?3:4;
//                                $StockLogs->create_date = date('Y-m-d H:i:s');
//                                $StockLogs->save();
                                array_push($modifyArr, '仓库编号' . $record[0] . ' sku' . $record[1] . " <font color='#00ff00'>库存添加成功</font><br>");
                            } else {
                                array_push($modifyArr, '仓库编号' . $record[0] . ' sku' . $record[1] . " <font color='#ff0000'>库存添加失败</font><br>");
                            }

                        }
                    }
                    $notice = implode("", $modifyArr);
                }
            }
            else
                {
                    $notice = '文件格式错误，请上传xlsx格式文件';
                }
        }
        return $this->render("import_stocks", ["notice" => $notice]);

    }
}
