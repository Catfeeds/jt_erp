<?php

namespace app\controllers;

use Yii;
use app\models\StockLocationArea;
use app\models\StockLocationAreaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StockLocationAreaController implements the CRUD actions for StockLocationArea model.
 */
class StockLocationAreaController extends Controller
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
     * Lists all StockLocationArea models.
     * @return mixed
     */
    public function actionIndex()
    {
        $param = Yii::$app->request->queryParams;
        if(!$param['stock_code']) {
            throw new NotFoundHttpException('仓库编号 不存在！', 403);
        }
        $searchModel = new StockLocationAreaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'stock_code' => $param['stock_code']
        ]);
    }

    /**
     * Displays a single StockLocationArea model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $param = Yii::$app->request->queryParams;
        if(!$param['stock_code']) {
            throw new NotFoundHttpException('仓库编号 不存在！', 403);
        }
        $model = new  StockLocationArea();
        $info = $model->find()
            ->select('stock_location_area.*')
            ->joinWith(['user_info'])
            ->where("stock_location_area.id={$id}")
            //->leftJoin('user', 'products_base.uid = user.id')
            //->asArray()
            ->one();
        return $this->render('view', [
            'model' => $info,
            'stock_code' => $param['stock_code']
        ]);
    }

    /**
     * Creates a new StockLocationArea model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $param = Yii::$app->request->queryParams;
        if(!$param['stock_code']) {
            throw new NotFoundHttpException('仓库编号 不存在！', 403);
        }
        $model = new StockLocationArea();
        if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $model->stock_code = $param['stock_code'];
            $model->create_date = date('Y-m-d H:i:s',time());
            $model->uid = Yii::$app->user->getId();
            if($model->save()) {
                return $this->redirect(['index', 'id' => $model->id,'stock_code'=>$param['stock_code']]);
            } else {
                throw new NotFoundHttpException('保存失败，请重试！', 403);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }

    }

    /**
     * Updates an existing StockLocationArea model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $param = Yii::$app->request->queryParams;
        if(!$param['stock_code']) {
            throw new NotFoundHttpException('仓库编号 不存在！', 403);
        }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id,'stock_code'=>$param['stock_code']]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'stock_code'=>$param['stock_code']
            ]);
        }
    }

    /**
     * Deletes an existing StockLocationArea model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $param = Yii::$app->request->queryParams;
        if(!$param['stock_code']) {
            throw new NotFoundHttpException('仓库编号 不存在！', 403);
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index','stock_code'=>$param['stock_code']]);
    }

    /**
     * Finds the StockLocationArea model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StockLocationArea the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StockLocationArea::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
