<?php

namespace app\controllers;

use Yii;
use app\models\AdFee;
use app\models\AdFeeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\User;

/**
 * AdFeeController implements the CRUD actions for AdFee model.
 */
class AdFeeController extends Controller
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
     * Lists all AdFee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdFeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $userMedel = new User();
        $is_admin = $userMedel->getGroupUsers();
        $is_select = 0;
        if($is_admin['is_select'] == 1) {
            $is_select = 1;
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'is_select' => $is_select
        ]);
    }

    /**
     * Displays a single AdFee model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $userMedel = new User();
        $is_admin = $userMedel->getGroupUsers();
        $is_select = 0;
        if($is_admin['is_select'] == 1) {
            $is_select = 1;
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'is_select' => $is_select
        ]);
    }

    /**
     * Creates a new AdFee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdFee();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AdFee model.
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
     * Deletes an existing AdFee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionImport()
    {
        if (Yii::$app->request->isPost)
        {
            $objectPHPExcel = new \PHPExcel();
            $file = UploadedFile::getInstanceByName('adData');
            if (strpos($file->name, ".xlsx") > 0)
            {
                $PHPReader = new \PHPExcel_Reader_Excel2007();
                $PHPExcel = $PHPReader->load($file->tempName);
                $currentSheet = $PHPExcel->getSheet(0);
                $allRow = $currentSheet->getHighestRow();
                $modifyArr = [];
                for ($currentRow = 2; $currentRow <= $allRow; $currentRow++)
                {
                    $record = [];
                    for ($column = 'A'; $column <= 'C'; $column++) {
                        $data = $currentSheet->getCell($column.$currentRow)->getValue();
                        array_push($record, $data);
                        // $val = $currentSheet->getCellByColumnAndRow($column, $currentRow)->getValue();

                    }

                    if (!empty($record[0]) && !empty($record[1]) && !empty($record[2]))
                    {
                        // 修改
                        $d = 25569;
                        $t = 24 * 60 * 60;
                        
                        $model = new AdFee();
                        $model->website_id = $record[0];
                        $model->ad_total = $record[1];
                        $model->ad_date = gmdate('Y-m-d', ($record[2] - $d) * $t);;
                        $model->save();
                    }
                }
                $notice = '文件导入完成';
            }
            else
            {
                $notice = '文件格式错误，请上传xlsx格式文件';
            }
        }

        return $this->render("import", ["notice" => $notice]);
    }

    /**
     * Finds the AdFee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdFee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdFee::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
