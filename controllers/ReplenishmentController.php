<?php

namespace app\controllers;

use app\models\ProductsSuppliers;
use app\models\ProductsVariant;
use app\models\Purchases;
use app\models\PurchaseForOrders;
use app\models\PurchasesItems;
use Yii;
use app\models\Replenishment;
use app\models\ReplenishmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ReplenishmentController implements the CRUD actions for Replenishment model.
 */
class ReplenishmentController extends Controller
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
     * Lists all Replenishment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReplenishmentSearch();
        $param = Yii::$app->request->queryParams;
        $param['ReplenishmentSearch']['status'] = '未采购';
        $dataProvider = $searchModel->search($param);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single Replenishment model.
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
     * Creates a new Replenishment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Replenishment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Replenishment model.
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
     * Deletes an existing Replenishment model.
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
     * Finds the Replenishment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Replenishment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Replenishment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 从采购需求生成采购单
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionAddPurchases()
    {

        $tr = Yii::$app->db->beginTransaction();
        $model = new Replenishment();
        $Replenishment = $model->find()->where(['status' => '未采购'])->asArray()->all();
        if (!$Replenishment) {
            throw new NotFoundHttpException('未采购需求信息为空！', 403);
        }
        $sku_list = [];
        $replen_list = [];
        $rep_list = [];
        foreach ($Replenishment as $row) {
            $sku_list[] = $row['sku_id'];
            $replen_list[$row['sku_id']] += $row['supplement_number'];
            $rep_list[$row['sku_id']] = $row;

            //把采购需求更新成已采购
            $UpdateReplenishment = Replenishment::findOne(['id' => $row['id']]);
            $UpdateReplenishment->status = '已采购';
            $UpdateReplenishment->save();
        }
        /*
        $ProductsSuppliers = new ProductsSuppliers();
        $ProductsSuppliersList = $ProductsSuppliers->find()->select('products_suppliers.*,suppliers.name')->where(['in', 'products_suppliers.sku', $sku_list])->leftJoin('suppliers', 'products_suppliers.supplier_id = suppliers.id')->asArray()->all();
        if (!$ProductsSuppliersList) {
            foreach ($sku_list as $row) {
                $supp_arr = array();
                $supp_arr['sku'] = $row;
                $supp_arr['supplier_id'] = 1;
                $supp_arr['name'] = 1688;
                $supp_arr['url'] = '';
                $supp_arr['min_buy'] = 1;
                $supp_arr['price'] = 0;
                $supp_arr['deliver_time'] = 0;
                $ProductsSuppliersList[] = $supp_arr;
            }
        }
        $products_supp_list = [];
        $no_supp_list = [];
        foreach ($ProductsSuppliersList as $row) {
            if (true || $row['min_buy'] <= $replen_list[$row['sku']]) {
                if (!$products_supp_list[$row['sku']]) {
                    $products_supp_list[$row['sku']] = $row;
                    $products_supp_list[$row['sku']]['order_id'][$rep_list[$row['sku']]['orders_id']] = $rep_list[$row['sku']]['orders_id'];
                    unset($no_supp_list[$row['sku']]);
                }
            } else {
                if (!$products_supp_list[$row['sku']]) {
                    $no_supp_list[$row['sku']] = $row['sku'];

                }
            }
        }

        $supp_list = [];
        foreach ($products_supp_list as $row) {
            $supp_list[$row['name']]['amaount'] += $row['price'] * $replen_list[$row['sku']];
            $supp_list[$row['name']]['sku'][] = $row;
            $supp_list[$row['name']]['order_id'] = $row['order_id'];
        }
        */

        $Purchases = new Purchases();

        $purchases_info = $Purchases->find()->where(['date_format(create_time ,\'%Y-%m-%d\' )' => date('Y-m-d')])->asArray()->orderBy('id desc')->one();
        $id = 1;
        if ($purchases_info) {
            $id_arr = explode('-', $purchases_info['order_number']);
            $id = $id_arr[1] + 1;
        }

        //生成采购单
        $addPurchases = new Purchases();
        $addPurchases->order_number = date('Ymd') . '-' . $id;
        $addPurchases->create_time = date('Y-m-d H:i:s');
        $addPurchases->amaount = 0;
        $addPurchases->supplier = '1688';
        $addPurchases->status = 0;
        $addPurchases->uid = Yii::$app->user->getId();
        if (!$addPurchases->save()) {
            $tr->rollBack();
            throw new NotFoundHttpException('采购单添加失败', 403);
        }
        //生成采购单明细
        foreach ($rep_list as $key => $row) {
            $product_sku = ProductsSuppliers::find()->where(['sku' => $row['sku_id']])->one();
            if($product_sku)
            {
                $buy_url = $product_sku->url;
            }
            $addPurchasesItems = new PurchasesItems();
            $addPurchasesItems->purchase_number = date('Ymd') . '-' . $id;
            $addPurchasesItems->sku = $row['sku_id'];
            $addPurchasesItems->qty = $replen_list[$row['sku_id']];
            $addPurchasesItems->buy_link = $buy_url;
            $addPurchasesItems->info = '';
            if (!$addPurchasesItems->save()) {
                $tr->rollBack();
                throw new NotFoundHttpException('采购单详情添加失败', 403);
            }

        }
/*
        foreach ($supp_list as $key => $row) {
            $addPurchases = new Purchases();
            $addPurchases->order_number = date('Ymd') . '-' . $id;
            $addPurchases->create_time = date('Y-m-d H:i:s');
            $addPurchases->amaount = $row['amaount'] ? $row['amaount'] : '0.00';
            $addPurchases->supplier = (string)$key ? (string)$key : '1688';
            $addPurchases->status = 0;
            $addPurchases->uid = Yii::$app->user->getId();
            if (!$addPurchases->save()) {
                $tr->rollBack();
                throw new NotFoundHttpException('采购单添加失败', 403);
            }

            foreach ($row['sku'] as $k => $v) {
                $addPurchasesItems = new PurchasesItems();
                $addPurchasesItems->purchase_number = date('Ymd') . '-' . $id;
                $addPurchasesItems->sku = $v['sku'];
                $addPurchasesItems->qty = $replen_list[$v['sku']];
                $addPurchasesItems->buy_link = $v['url'];
                $addPurchasesItems->info = '';
                if (!$addPurchasesItems->save()) {
                    $tr->rollBack();
                    throw new NotFoundHttpException('采购单详情添加失败', 403);
                }

            }

        }
*/
        $tr->commit();
        return $this->redirect(['purchases/index']);

    }

    /**
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSelectAdd()
    {
        $data = Yii::$app->request->post();
        if (!isset($data['id_arr']) || !$data['id_arr'])
        {
            return json_encode(array('status'=>0,''=>'请勾选相应采购单'));
        }
        $id_arr = $data['id_arr'];
        $id_arr_str = implode(',',$id_arr);
        $tr = Yii::$app->db->beginTransaction();
        $Replenishment = Yii::$app->db->createCommand("select * from replenishment where id in ({$id_arr_str}) and status = '未采购'")->queryAll();
        if (!$Replenishment)
        {
            return json_encode(array('status'=>0,'msg'=>'采购单信息为空'));
        }
        $sku_list = [];
        $replen_list = [];
        $rep_list = [];
        foreach ($Replenishment as $row)
        {
            $sku_list[] = $row['sku_id'];
            $replen_list[$row['sku_id']] += $row['supplement_number'];
            $rep_list[$row['sku_id']] = $row;

            //把采购需求更新成已采购
            $UpdateReplenishment = Replenishment::findOne(['id' => $row['id']]);
            $UpdateReplenishment->status = '已采购';
            $UpdateReplenishment->save();
        }

        $Purchases = new Purchases();

        $purchases_info = $Purchases->find()->where(['date_format(create_time ,\'%Y-%m-%d\' )' => date('Y-m-d')])->asArray()->orderBy('id desc')->one();
        $id = 1;
        if ($purchases_info)
        {
            $id_arr = explode('-', $purchases_info['order_number']);
            $id = $id_arr[1] + 1;
        }

        //生成采购单
        $addPurchases = new Purchases();
        $addPurchases->order_number = date('Ymd') . '-' . $id;
        $addPurchases->create_time = date('Y-m-d H:i:s');
        $addPurchases->amaount = 0;
        $addPurchases->supplier = '1688';
        $addPurchases->status = 0;
        $addPurchases->uid = Yii::$app->user->getId();
        if (!$addPurchases->save())
        {
            $tr->rollBack();
            return json_encode(array('status'=>0,'msg'=>'采购单添加失败'));
        }
        //生成采购单明细
        foreach ($rep_list as $key => $row)
        {
            $buy_url = '';
            $product_sku = ProductsSuppliers::find()->where(['sku' => $row['sku_id']])->one();
            if ($product_sku)
            {
                $buy_url = $product_sku->url;
            }
            $addPurchasesItems = new PurchasesItems();
            $addPurchasesItems->purchase_number = date('Ymd') . '-' . $id;
            $addPurchasesItems->sku = $row['sku_id'];
            $addPurchasesItems->qty = $replen_list[$row['sku_id']];
            $addPurchasesItems->buy_link = $buy_url;
            $addPurchasesItems->info = '';
            if (!$addPurchasesItems->save())
            {
                $tr->rollBack();
                return json_encode(array('status'=>0,'msg'=>'采购单详情添加失败'));
            }
        }
        $tr->commit();
        return json_encode(array('status'=>1,'msg'=>'采购单生成成功'));
    }

}
