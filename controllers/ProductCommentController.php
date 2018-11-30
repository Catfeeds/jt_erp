<?php

namespace app\controllers;

use Yii;
use app\models\ProductComment;
use app\models\ProductCommentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;


/**
 * ProductCommentController implements the CRUD actions for ProductComment model.
 */
class ProductCommentController extends Controller
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

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }

    /**
     * Lists all ProductComment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $id = Yii::$app->request->get("website_id");
        if (!empty($id))
        {
            Yii::$app->request->queryParams["ProductCommentSearch[website_id]"] = $id;
        }
        $searchModel = new ProductCommentSearch();
        $dataProvider = $searchModel->searchByCond(Yii::$app->request->queryParams);

        $userMedel = new User();
        $is_admin = $userMedel->getGroupUsers();
        $is_select = 0;
        if($is_admin['is_select'] == 1) {
            $is_select = 1;
        }

        return $this->render('index', [
            'websiteId' => $id,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'is_select' => $is_select
        ]);
    }

    /**
     * Displays a single ProductComment model.
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
     * Creates a new ProductComment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // 产品评论创建 jieosn 2018.10.17
    public function actionCreate()
    {
        $model = new ProductComment();
        $websiteId = Yii::$app->request->get("website_id");
        if ($data = Yii::$app->request->post('ProductComment')) {
            if (strpos($data['website_id'], ' ')) {
                // 多个站点
                $website_id_arr = explode(' ', $data['website_id']);
                $saveData = [];
                foreach ($website_id_arr as $k => $v){
                    $saveData[$k]['website_id'] = $v;
                    $saveData[$k]['name']       = $data['name'];
                    $saveData[$k]['phone']      = $data['phone'];
                    $saveData[$k]['body']       = $data['body'];
                    $saveData[$k]['isshow']     = $data['isshow'];
                    $saveData[$k]['add_time']   = $data['add_time'];
                }
                try {
                    Yii::$app->db->createCommand()->batchInsert(ProductComment::tableName(), ['website_id', 'name', 'phone', 'body', 'isshow', 'add_time'], $saveData)->execute();
                    return $this->redirect(['index', 'website_id' => $website_id_arr[0]]);
                } catch(Exception $e) {
                    echo $e->getError();
                }
                    
            } else {
                // 单个
                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }
        return $this->render('create', [
            'websiteId' => $websiteId,
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProductComment model.
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
     * Deletes an existing ProductComment model.
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
     * Finds the ProductComment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductComment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductComment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
