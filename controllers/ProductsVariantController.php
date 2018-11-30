<?php

namespace app\controllers;

use app\models\OrdersItem;
use app\models\Suppliers;
use app\models\ProductsSuppliers;

use app\models\WebsitesSku;
use Yii;
use app\models\ProductsVariant;
use app\models\ProductsBase;
use app\models\ProductsVariantSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use app\models\User;

use yii\web\UploadedFile;

/**
 * ProductsVariantController implements the CRUD actions for ProductsVariant model.
 */
class ProductsVariantController extends Controller
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
     * Lists all ProductsVariant models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductsVariantSearch();
        $base_model = new ProductsBase();
        $param = Yii::$app->request->queryParams;
        if(!$param['spu_id']) {
            throw new NotFoundHttpException('spu_id 不存在！', 403);
        }
        $spu = $base_model->find()->select('spu')->where('id='.$param['spu_id'])->asArray()->one();
        $spu = $spu['spu'];
        $param['spu'] = $spu;
        $dataProvider = $searchModel->search($param);

        $base_model = new ProductsBase();
        $spu = $base_model->find()->select('spu')->where('id='.$param['spu_id'])->asArray()->one();
        $spu = $spu['spu'];
        if(!$spu) {
            throw new NotFoundHttpException('spu 不存在！', 403);
        }
        $userMedel = new User();
        $is_admin = $userMedel->getGroupUsers();
        $is_select = 0;
        if($is_admin['is_select'] == 1) {
            $is_select = 1;
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'spu_id' => $param['spu_id'],
            'spu' => $spu,
            'is_select' => $is_select
        ]);
    }

    /**
     * Displays a single ProductsVariant model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $spu_id = Yii::$app->request->get('spu_id');

        if(!$spu_id) {
            throw new NotFoundHttpException('spu_id 不存在！', 403);
        }
        // 读取供应商关联信息
        $proSupModel = new ProductsSuppliers();
        #where("level<2")->
        $sku_list = $this->findModel($id);
        ;
        $sku = $sku_list->sku;

        $pro_sup = $proSupModel->find()
            ->select('products_suppliers.*,suppliers.name')
            ->leftJoin('suppliers','suppliers.id=products_suppliers.supplier_id')
            ->where("products_suppliers.sku = '{$sku}'")
            ->asArray()->all();

        $userMedel = new User();
        $is_admin = $userMedel->getGroupUsers();
        $is_select = 0;
        if($is_admin['is_select'] == 1) {
            $is_select = 1;
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'spu_id' => $spu_id,
            'pro_sup' =>$pro_sup,
            'is_select' => $is_select
        ]);

    }

    /**
     * Creates a new ProductsVariant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductsVariant();
        $spu_id = Yii::$app->request->get('spu_id');
        if(!$spu_id) {
            throw new NotFoundHttpException('spu_id 不存在！', 403);

        }
        $base_model = new ProductsBase();
        $spu = $base_model->find()->select('spu')->where('id='.$spu_id)->asArray()->one();
        $spu = $spu['spu'];
        if(!$spu) {
            throw new NotFoundHttpException('spu 不存在！', 403);
        }

        // 读取供应商信息
        $supmodel = new Suppliers();
        #where("level<2")->
        $suppliers = $supmodel->find()->where('status = 1')->all();
        //$categoriesData = [0 => '无'];

        foreach ($suppliers as $row)
        {
            $suppliersData[$row->id] = $row->name;
        }

        if(Yii::$app->request->post()) {

            $model->load(Yii::$app->request->post());
            $post_data = $_POST;
            $images = $post_data['image_val'];
            if(!$images) {
                throw new NotFoundHttpException('请选择图片！', 403);
            }
            if(count($images) > 1) {
                throw new NotFoundHttpException('只能选择一张图片！', 403);
            }
            $model->image = $images[0];
            $param = Yii::$app->request->post('ProductsVariant');
            $model->spu = $spu;
            $size = str_replace('，',',', $param['size']);
            $sizes = explode(',',$size);
            foreach($sizes as $size)
            {
                $model->setIsNewRecord(true);
                unset($model->id);
                $model->size = strtoupper($size);
                $sku = $this->makeSku($spu, $param['color'], $size);
                if($sku == 1001) {
                    throw new NotFoundHttpException( '变体保存失败，没有尺码的产品颜色不可以重复！', 403);
                } elseif($sku == 1002) {
                    throw new NotFoundHttpException( $sku. '变体保存失败，同一颜色尺码不可以重复！', 403);
                }
                $model->sku = $sku;
                $model->create_time = date('Y-m-d H:i:s',time());
                if($model->save()) {
                    //return $this->redirect(['view', 'id' => $model->id,'spu_id'=>$spu_id]);
                } else {

                    throw new NotFoundHttpException( $sku. print_r($model->getErrors(), true).'保存失败，请重试！', 403);
                }
            }
            return $this->redirect(['index', 'id' => $model->id,'spu_id'=>$spu_id]);


        } else {
            return $this->render('create', [
                'model' => $model,
                'suppliers' => $suppliersData,
            ]);
        }
    }

    /**
     * Updates an existing ProductsVariant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $tr = Yii::$app->db->beginTransaction();

        $model = $this->findModel($id);
        $spu_id = Yii::$app->request->get('spu_id');
        if(!$spu_id) {
            throw new NotFoundHttpException('spu_id 不存在！', 403);

        }
        $base_model = new ProductsBase();
        $spu = $base_model->find()->select('spu')->where('id='.$spu_id)->asArray()->one();
        $spu = $spu['spu'];
        if(!$spu) {
            throw new NotFoundHttpException('spu 不存在！', 403);
        }
        // 读取供应商信息
        $supmodel = new Suppliers();
        #where("level<2")->
        $suppliers = $supmodel->find()->where('status = 1')->all();
        //$categoriesData = [0 => '无'];

        foreach ($suppliers as $row)
        {
            $suppliersData[$row->id] = $row->name;
        }

        // 读取供应商关联信息
        $proSupModel = new ProductsSuppliers();
        #where("level<2")->
        $sku_list =  $model;
        $sku = $sku_list->sku;

        $pro_sup = $proSupModel->find()->where("sku = '{$sku}'")->asArray()->all();
        //$categoriesData = [0 => '无'];

        foreach ($suppliers as $row)
        {
            $suppliersData[$row->id] = $row->name;
        }
        if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $post_data = $_POST;
            $images = $post_data['image_val'];
            if(!$images) {
                throw new NotFoundHttpException('请选择图片！', 403);
            }
            if(count($images) > 1) {
                throw new NotFoundHttpException('只能选择一张图片！', 403);
            }
            $model->image = $images[0];

            $model->create_time = date('Y-m-d H:i:s',time());
            if($model->save()) {
                if($post_data['ProductsVariant']['sku'] != $sku) {

                    if(OrdersItem::findOne(array('sku'=>$sku))) {
                        if(!OrdersItem::updateAll(array('sku'=>$post_data['ProductsVariant']['sku']),array('sku'=>$sku))) {
                            $tr->rollBack();
                            throw new NotFoundHttpException('订单sku表sku修改失败，请重试！', 403);
                        }
                    }
                    if(ProductsSuppliers::findOne(array('sku'=>$sku))) {
                        if(!ProductsSuppliers::updateAll(array('sku'=>$post_data['ProductsVariant']['sku']),array('sku'=>$sku))) {
                            $tr->rollBack();
                            throw new NotFoundHttpException('SKU供应商表sku修改失败，请重试！', 403);
                        }
                    }
                        if(WebsitesSku::findOne(array('sku'=>$sku))) {
                            if(!WebsitesSku::updateAll(array('sku'=>$post_data['ProductsVariant']['sku']),array('sku'=>$sku))) {
                                $tr->rollBack();
                                throw new NotFoundHttpException('websiteSku表sku修改失败，请重试！', 403);
                            }
                        }
                    $sku = $post_data['ProductsVariant']['sku'];

                }
                if($post_data['products_suppliers']){
                    foreach ($post_data['products_suppliers'] as $row ) {
                        $pro_supp_model = '';
                        if($row['id']) {
                            $pro_supp_model = ProductsSuppliers::findOne($row['id']);
                        } else {
                            $pro_supp_model = new ProductsSuppliers();

                        }
                        $pro_supp_model->supplier_id = $row['supplier_id'];
                        $pro_supp_model->sku = $sku;
                        $pro_supp_model->url = $row['url'];
                        $pro_supp_model->min_buy = $row['min_buy'];
                        $pro_supp_model->price = $row['price'];
                        $pro_supp_model->deliver_time = $row['deliver_time'];
//                        $pro_supp_model->setIsNewRecord(true);
                        if($pro_supp_model->save()) {
                        } else {
                            $tr->rollBack();
                            throw new NotFoundHttpException( $sku.'新建供应商失败'. print_r($model->getErrors(), true).'保存失败，请重试！', 403);
                        }
                    }
                }
                $tr->commit();
                return $this->redirect(['view', 'id' => $model->id,'spu_id'=>$spu_id]);
            } else {
                throw new NotFoundHttpException('保存失败，请重试！', 403);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'suppliers' => $suppliersData,
                'product_supplier' => $pro_sup
            ]);
        }

    }

    /**
     * Deletes an existing ProductsVariant model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $spu_id = Yii::$app->request->get('spu_id');
        if(!$spu_id) {
            throw new NotFoundHttpException('spu_id 不存在！', 403);

        }
        $this->findModel($id)->delete();

        return $this->redirect(['index','spu_id'=>$spu_id]);
    }

    /**
     * Finds the ProductsVariant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductsVariant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductsVariant::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    private function makeSku($spu,$color,$size) {
        $model = new ProductsVariant();
        $sku_list = $model->find()
            ->select('spu,color,size,sku')
            ->where("spu='{$spu}'")
            ->asArray()
            ->all();
        if(!$sku_list) {
            $color_sn = 1;
        } else {
            $color_count = array();
            $is_color = '';
            $is_size = '';
            foreach ($sku_list as $v) {
                $color_count[$v['color']] = 1;
                if($v['color'] == $color) {
                    $is_color = substr($v['sku'],8,2);
                    if($v['size'] == $size && $size) $is_size = $size;
                }
            }
            if($is_color && !$size) {
                return 1001;
            }
            if($is_color) {
                $color_sn = $is_color;
            } else {
                $color_sn = count($color_count)+1;
            }
            if($is_size && $size) {
                return 1002;
            }
        }

        return $spu.str_pad($color_sn,2,0,STR_PAD_LEFT).str_pad(strtoupper($size),3,0,STR_PAD_LEFT);

    }
    /**
     * 首图上伟
     * @return string
     */
    public function actionImageUpload()
    {
        $model = new ProductsVariant();

        $imageFile = UploadedFile::getInstance($model, Yii::$app->request->get('attribute', 'image'));

        $directory = Yii::getAlias('@imgDir') . DIRECTORY_SEPARATOR . date('Ymd') . DIRECTORY_SEPARATOR;

        if (!is_dir($directory)) {
            FileHelper::createDirectory($directory);
        }

        if ($imageFile) {
            $uid = uniqid(time(), true);
            $fileName = $uid . '.' . $imageFile->extension;
            $filePath = $directory . $fileName;
            if ($imageFile->saveAs($filePath)) {
                $path = Yii::getAlias('@imgPath').'/'. date('Ymd') . '/' . $fileName;
                return Json::encode([
                    'files' => [
                        [
                            'name' => $fileName,
                            'size' => $imageFile->size,
                            'url' => $path,
                            'thumbnailUrl' => $path,
                            'deleteUrl' => 'image-delete?name=' . $fileName,
                            'deleteType' => 'POST',
                        ],
                    ],
                ]);
            }
        }

        return '';
    }

    public function actionUpdateColor(){
        $color = Yii::$app->request->post('color');
        $size = Yii::$app->request->post('size');
        $id = Yii::$app->request->post('id');
        $spu = Yii::$app->db->createCommand("select spu from websites where id = {$id}")->queryOne();
        $spu = $spu['spu'];
        if($sku = Yii::$app->db->createCommand("select * from products_variant where color = '{$color}' and size = '{$size}' and spu = '{$spu}'")->queryOne()){
            echo $sku['sku'].'&'.$sku['image'];
        }else{
            echo '';
        }
    }


}
