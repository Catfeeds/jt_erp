<?php

namespace app\controllers;

use Yii;
use app\models\StockLocationCode;
use app\models\StockLocationCodeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StockLocationCodeController implements the CRUD actions for StockLocationCode model.
 */
class StockLocationCodeController extends Controller
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
     * Lists all StockLocationCode models.
     * @return mixed
     */
    public function actionIndex()
    {
        $param = Yii::$app->request->queryParams;
        if(!$param['stock_code']) {
            throw new NotFoundHttpException('仓库编号 不存在！', 403);
        }
        if(!$param['area_code']) {
            throw new NotFoundHttpException('库区编号 不存在！', 403);
        }
        $searchModel = new StockLocationCodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'stock_code' => $param['stock_code'],
            'area_code' => $param['area_code']
        ]);
    }

    /**
     * Displays a single StockLocationCode model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $param = Yii::$app->request->queryParams;
        if(!$param['stock_code']) {
            throw new NotFoundHttpException('仓库编号 不存在！', 403);
        }
        if(!$param['area_code']) {
            throw new NotFoundHttpException('库位编号 不存在！', 403);
        }
        $model = new  StockLocationCode();

        $info = $model->find()
            ->select('stock_location_code.*')
            ->joinWith(['user_info'])
            ->where("stock_location_code.id={$id}")
            //->leftJoin('user', 'products_base.uid = user.id')
            //->asArray()
            ->one();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'stock_code'=>$param['stock_code'],
            'area_code'=>$param['area_code']


        ]);
    }

    /**
     * Creates a new StockLocationCode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $param = Yii::$app->request->queryParams;
        if(!$param['stock_code']) {
            throw new NotFoundHttpException('仓库编号 不存在！', 403);
        }
        if(!$param['area_code']) {
            throw new NotFoundHttpException('库区编号 不存在！', 403);
        }
        $model = new StockLocationCode();
        if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $model->stock_code = $param['stock_code'];
            $model->area_code = $param['area_code'];
            $model->create_date = date('Y-m-d H:i:s',time());
            $model->uid = Yii::$app->user->getId();
            if($model->save()) {
                return $this->redirect(['index', 'id' => $model->id,'stock_code'=>$param['stock_code'],'area_code'=>$param['area_code']]);
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
     * Updates an existing StockLocationCode model.
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
        if(!$param['area_code']) {
            throw new NotFoundHttpException('库位编号 不存在！', 403);
        }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id,'stock_code'=>$param['stock_code'],'area_code'=>$param['area_code']]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'stock_code'=>$param['stock_code'],
                'area_code'=>$param['area_code']
            ]);
        }
    }

    /**
     * Deletes an existing StockLocationCode model.
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
        if(!$param['area_code']) {
            throw new NotFoundHttpException('库位编号 不存在！', 403);
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index','stock_code'=>$param['stock_code'],'area_code'=>$param['area_code']]);
    }

    /**
     * Finds the StockLocationCode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StockLocationCode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StockLocationCode::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
