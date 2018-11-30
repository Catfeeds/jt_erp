<?php

namespace app\controllers;

use Yii;
use app\models\ReceiptAbnormal;
use app\models\ReceiptAbnormalSearch;
use app\models\ReceiptAbnormalLogs;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ReceiptAbnormalController implements the CRUD actions for ReceiptAbnormal model.
 */
class ReceiptAbnormalController extends Controller
{
    /**
     * {@inheritdoc}
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
     * Lists all ReceiptAbnormal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReceiptAbnormalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ReceiptAbnormal model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $abLogsModel = new ReceiptAbnormalLogs();
        $logsInfo = $abLogsModel->find()->where("receipt_abnormal_id={$id}")->all();
        return $this->render('view', [
            'model'    => $this->findModel($id),
            'logsInfo' => $logsInfo,
        ]);
    }

    /**
     * Creates a new ReceiptAbnormal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        date_default_timezone_set('Asia/Shanghai');
        $model = new ReceiptAbnormal();
        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            $data['create_time'] = date('Y-m-d H:i:s', time());
            $data['create_uid']  = Yii::$app->user->id;
            $model->attributes = $data;
            if ($model->load($data) && $model->validate() && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ReceiptAbnormal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }

    /**
     * Deletes an existing ReceiptAbnormal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ReceiptAbnormal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ReceiptAbnormal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReceiptAbnormal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // 处理回复
    // jieson 2018.10.10
    public function actionHandlemsg()
    {
        date_default_timezone_set('Asia/Shanghai');
        // 异常收货单状态 0待采购处理，1待库房处理，2处理完成
        $logsModel   = new ReceiptAbnormalLogs();
        $raModel = ReceiptAbnormal::findOne(Yii::$app->request->post('raId'));
        $type = Yii::$app->request->post('type');
        if ($type ==  '1') {
            $raModel->status = 1;
        } elseif ($type == '2') {
            $raModel->status = 0;
        }
        $data['receipt_abnormal_id'] = Yii::$app->request->post('raId');
        $data['dealContents']        = Yii::$app->request->post('contents');
        $data['create_uid']          = Yii::$app->user->id;
        $data['create_time']         = date('Y-m-d H:i:s', time());
        $data['type']                = $type;
        try {
            $logsModel->attributes = $data;
            $logsModel->load($data);
            if ($logsModel->save()) {
                $raModel->save();
                return 'success';
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
        
    }

    // 处理完成
    public function actionHandledone()
    {
        date_default_timezone_set('Asia/Shanghai');
        $logsModel   = new ReceiptAbnormalLogs();
        $raModel = ReceiptAbnormal::findOne(Yii::$app->request->post('raId'));
        $raModel->status = 2;

        $data['receipt_abnormal_id'] = Yii::$app->request->post('raId');
        $data['dealContents']        = '处理完成';
        $data['create_uid']          = Yii::$app->user->id;
        $data['create_time']         = date('Y-m-d H:i:s', time());
        $data['type']                = 2;
        try {
            $logsModel->attributes = $data;
            $logsModel->load($data);
            if ($logsModel->save()) {
                $raModel->save();
                return 1;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
