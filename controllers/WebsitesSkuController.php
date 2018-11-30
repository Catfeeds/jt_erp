<?php

namespace app\controllers;

use app\models\Websites;
use Yii;
use app\models\WebsitesSku;
use app\models\WebsitesSkuBaseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WebsitesSkuController implements the CRUD actions for WebsitesSku model.
 */
class WebsitesSkuController extends Controller
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
     * Lists all WebsitesSku models.
     * @return mixed
     */
    public function actionIndex()
    {
        $productModel = new Websites();
        $skuModel = new WebsitesSku();
        $pid = intval($_GET['id']);
        if (isset($_GET['action']) && $_GET['action'] == 'clear') {
            Yii::$app->db->createCommand('DELETE FROM ' . $skuModel->tableName() . ' WHERE website_id=' . $pid)->execute();
            return $this->redirect(['index', 'id' => $pid]);
        }


        $product = $productModel->findOne($pid);
        $sizes = $product_style = false;
        if ($product->size) {
            $sizes = explode(',', $product->size);
        }



        if ($product->product_style) {
            $product_style = json_decode($product->product_style);
        }
        if ($product_style || $sizes) {
            $skus = [];
            if ($product_style && $sizes) {
                foreach ($product_style as $key => $style) {
                    foreach ($sizes as $size) {

                        $s = explode('#', $size);
//                        echo $s[0].trim($style->name),'<br>';
                        $sign = md5(trim($s[0]) . trim($style->name));
                        $sku = sprintf("%'.02d", $key + 1) . sprintf("%'.03s", strtoupper($s[0]));
                        $skus[] = [
                            'website_id' => $product->id,
//                            'sku' => $product->sku . mb_substr($sku, 0, 5,'utf-8'),
                            'sku' => '',
                            'color' => trim($style->name),
                            'size' => trim($s[0]),
                            'sign' => $sign,
                            'images' => $style->image
                        ];
                    }
                }
            } elseif ($sizes) {
                $images = json_decode($product->images);
                foreach ($sizes as $size) {
                    $s = explode('#', $size);
                    $sign = md5(trim($s[0]));
                    $sku = '00' . sprintf("%'.03s", strtoupper($s[0]));
                    $skus[] = [
                        'website_id' => $product->id,
//                        'sku' => $product->sku . mb_substr($sku, 0, 5,'utf-8'),
                        'sku' => '',
                        'color' => '',
                        'size' => trim($s[0]),
                        'sign' => $sign,
                        'images' => $images[0]
                    ];
                }

            } elseif ($product_style) {
                foreach ($product_style as $key => $style) {
                    $sign = md5(trim($style->name));
                    $sku = sprintf("%'.02d", $key + 1) . '000';
                    $skus[] = [
                        'website_id' => $product->id,
                        'sku' => $product->spu . mb_substr($sku, 0, 5,'utf-8'),
                        'color' => trim($style->name),
                        'size' => '',
                        'sign' => $sign,
                        'images' => $style->image
                    ];
                }
            }
            foreach ($skus as $data) {
                if (!$skuModel->findOne(['website_id' => $data['website_id'], 'sign' => $data['sign']])) {
//                    var_dump($data);die;
                    $skuModel->setIsNewRecord(true);
                    $skuModel->attributes = $data;
                    unset($skuModel->id);
                    $skuModel->save();
                }

            }

        } else {
            $sign = md5('');
            $sku = [
                'website_id' => $product->id,
//                'sku' => $product->sku . '00000',
                'sku' => '',
                'color' => '',
                'size' => '',
                'sign' => $sign
            ];
            if (!$skuModel->findOne(['website_id' => $product->id, 'sign' => $sign])) {
                $images = json_decode($product->images);
                $skuModel->attributes = [
                    'website_id' => $product->id,
//                    'sku' => $product->sku . '00000',
                    'sku' => '',
                    'color' => '',
                    'size' => '',
                    'sign' => $sign,
                    'images' => $images[0],
                ];
                $skuModel->save();
            }


        }

        $searchModel = new WebsitesSkuBaseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WebsitesSku model.
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
     * Creates a new WebsitesSku model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WebsitesSku();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WebsitesSku model.
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

    public function actionUpdateSku()
    {
        $id = Yii::$app->request->post('id');
        $stock = Yii::$app->request->post('stock');
        $model = $this->findModel($id);
        $model->out_stock = $stock;
        if($model->save()){
            echo '修改成功';
        }
    }

    /**
     * Deletes an existing WebsitesSku model.
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
     * Finds the WebsitesSku model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WebsitesSku the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WebsitesSku::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSku()
    {

        $id = Yii::$app->request->post('id');
        $sku = Yii::$app->request->post('sku');

        $model = $this->findModel($id);

        $model->sku = $sku;
//        $model->sign = md5($size . $color);
        if ($model->save()) {
            echo 200;
        } else {
            echo 500;
        };
    }

}
