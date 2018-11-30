<?php

namespace app\controllers;

use app\models\ProductsVariant;
use app\models\Websites;
use Yii;
use app\models\ProductsBase;
use app\models\ProductsBaseSearch;
use app\models\ProductsVariantSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Categories;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use app\models\ProductsSuppliers;
use app\models\Suppliers;
use app\models\User;

use yii\web\UploadedFile;



/**
 * ProductsBaseController implements the CRUD actions for ProductsBase model.
 */
class ProductsBaseController extends Controller
{
    private $cat_rule = array(
        'bag' =>'A',
        'watch' => 'B',
        'shoe' => 'C',
        'cloth' => 'D',
        'home' => 'E',
        'out' => 'F',
        'cos' => 'G',
        'tool' =>'H',
        'ccc' => 'I',
        'hot' => 'J',
        'tech' => 'K',
        'toy' => 'L',
        'jew' => 'M',
        'pet' => 'N',
        'art' => 'O',
        'cook' => 'P',
        'sport' => 'Q',
        'fit' => 'R',
        'Black' => 'S'

    );
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
     * Lists all ProductsBase models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductsBaseSearch();
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);
        $userMedel = new User();
        $is_admin = $userMedel->getGroupUsers();
        $is_group = 1;
        $is_select = 0;
        if($is_admin['is_admin'] == 0 && $is_admin['leader'] == 0) {
            $is_group = 0;
        }
        if($is_admin['is_select'] == 1) {
            $is_select = 1;
        }

        $skuModel = new ProductsVariantSearch();
        $skuData = $skuModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'skuModel' => $skuModel,
            'skuData' => $skuData,
            'is_group' => $is_group,
            'is_select' => $is_select
        ]);
    }

    /**
     * Displays a single ProductsBase model.
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
        $model = new  ProductsBase();
        $info = $model->find()
            ->select('products_base.*')
            ->joinWith(['categories_info'])
            ->joinWith(['user_info'])
            ->where("products_base.id={$id}")
            //->leftJoin('user', 'products_base.uid = user.id')
            //->asArray()
            ->one();
        return $this->render('view', [
            'model' => $info,
            'is_select' => $is_select
        ]);
    }

    /**
     * Creates a new ProductsBase model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductsBase();
        // 读取分类信息
        $catmodel = new Categories();
        #where("level<2")->
        $categories = $catmodel->find()->all();
        //$categoriesData = [0 => '无'];

        foreach ($categories as $category)
        {
            $categoriesData[$category->id] = $category->cn_name;
        }
        if(Yii::$app->request->post()) {
            $post_data = $_POST;
            $param = $post_data['ProductsBase'];
            $model->load(Yii::$app->request->post());
            $model->spu = $this->makeSpu($param['categorie'],$param['sex'],$param['product_type']);
            $images = $post_data['image_val'];
            if(!$images) {
                throw new NotFoundHttpException('请选择图片！', 403);
            }
            if(count($images) > 1) {
                throw new NotFoundHttpException('只能选择一张图片！', 403);
            }
            $model->product_type = $post_data['product_type'];
            $model->image = $images[0];
            $model->create_time = date('Y-m-d H:i:s',time());
            $model->uid = Yii::$app->user->getId();
            if($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                throw new NotFoundHttpException('保存失败，请重试！', 403);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'categories' => $categoriesData,
            ]);
        }
    }

    /**
     * Updates an existing ProductsBase model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $tr = Yii::$app->db->beginTransaction();


        $model = $this->findModel($id);
        // 读取分类信息
        $catmodel = new Categories();
        #where("level<2")->
        $categories = $catmodel->find()->all();
        //$categoriesData = [0 => '无'];

        foreach ($categories as $category)
        {
            $categoriesData[$category->id] = $category->cn_name;
        }
        $old_spu = $model->spu;
        if(Yii::$app->request->post()) {
            $post_data = $_POST;
            $model->load(Yii::$app->request->post());
            $images = $post_data['image_val'];
            if(!$images) {
                throw new NotFoundHttpException('请选择图片！', 403);
            }
            if(count($images) > 1) {
                throw new NotFoundHttpException('只能选择一张图片！', 403);
            }
            $model->product_type = $post_data['product_type'];
            $model->image = $images[0];
            $model->create_time = date('Y-m-d H:i:s',time());
//            $model->uid = Yii::$app->user->getId();
            if($model->save()) {
                ###修改其他关联表SPU
                if($post_data['ProductsBase']['spu'] != $old_spu) {
                    if(ProductsVariant::findOne(array('spu'=>$old_spu))) {
                        if(!ProductsVariant::updateAll(array('spu'=>$post_data['ProductsBase']['spu']),array('spu'=>$old_spu))) {
                            $tr->rollBack();
                            throw new NotFoundHttpException('sku表spu修改失败，请重试！', 403);
                        }
                    }
                    if(Websites::findOne(array('spu'=>$old_spu))) {
                        if(!Websites::updateAll(array('spu'=>$post_data['ProductsBase']['spu']),array('spu'=>$old_spu))) {
                            $tr->rollBack();
                            throw new NotFoundHttpException('website表spu修改失败，请重试！', 403);
                        }
                    }



                    //websites
                }
                $tr->commit();

                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                throw new NotFoundHttpException('保存失败，请重试！', 403);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'categories' => $categoriesData,
            ]);
        }
    }

    /**
     * Deletes an existing ProductsBase model.
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
     * Finds the ProductsBase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductsBase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {

        if (($model = ProductsBase::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    #生成货号
    #$cat_id{string 类别ID}
    #$sex{string 性别 1 => "男", 2 => "女"}
    #$product_type{string 产品类型 1 => "普货", 2 => "特货"}
    public function makeSpu($cat_id,$sex,$product_type) {
       $model = new ProductsBase();
       $count = $model->find()->count();
       $max_cat = $this->getMaxCat($cat_id);
       $sex_label = $sex == 1 ? 'M' : 'F';
       $product_type = $product_type == 1 ? 'P' : 'M';
       $cat_name = '';
       if($max_cat['en_name']) {

           if(isset($this->cat_rule[$max_cat['en_name']]) && !empty($this->cat_rule[$max_cat['en_name']])) {
               $cat_name = $this->cat_rule[$max_cat['en_name']];
           }
       }
        $cat_name = $cat_name?$cat_name:'Z';
       return $cat_name.str_pad($count+1,5,0,STR_PAD_LEFT).$product_type.$sex_label;

    }

    #获取对应1级大分类
    public function getMaxCat($cat_id) {
        $catmodel = new Categories();
        $categories = $catmodel->find()->where("id={$cat_id}")->one();
        if(!$categories) return fales;
        if($categories['level'] ==0 ) return $categories;
        $this->getMaxCat($categories['id']);

    }
    /**
     * 首图上伟
     * @return string
     */
    public function actionImageUpload()
    {
        $model = new ProductsBase();

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

    public function actionAddSuppliers($spu)
    {
        if(!$spu) {
            throw new NotFoundHttpException( 'SPU不可以为空', 403);
        }
        $model = new ProductsSuppliers();
        // 读取供应商信息
        $supmodel = new Suppliers();
        $suppliers = $supmodel->find()->where('status = 1')->all();

        foreach ($suppliers as $row)
        {
            $suppliersData[$row->id] = $row->name;
        }
        $model_variant = new ProductsVariant();
        $pro_sup = $model_variant->find()
            ->select('products_variant.spu,products_variant.sku,products_suppliers.id,products_suppliers.supplier_id,products_suppliers.url,products_suppliers.min_buy,products_suppliers.price,products_suppliers.deliver_time')
            ->leftJoin('products_suppliers','products_variant.sku = products_suppliers.sku')
            ->where("products_variant.spu = '{$spu}'")
            ->asArray()->all();

        $post_data = $_POST;
        if($post_data['products_suppliers'] || $post_data['update_products_suppliers']){
            if($pro_sup && $post_data['products_suppliers']) {
                foreach ($pro_sup as $v) {
                    foreach ($post_data['products_suppliers'] as $row ) {
                        $pro_supp_model = new ProductsSuppliers();
                        $pro_supp_model->supplier_id = $row['supplier_id'];
                        $pro_supp_model->sku = $v['sku'];
                        $pro_supp_model->url = $row['url'];
                        $pro_supp_model->min_buy = $row['min_buy'];
                        $pro_supp_model->price = $row['price'];
                        $pro_supp_model->deliver_time = $row['deliver_time'];
                        $pro_supp_model->setIsNewRecord(true);
                        if($pro_supp_model->save()) {
                        } else {
                            throw new NotFoundHttpException( $v['sku'].'新建供应商失败'. print_r($model->getErrors(), true).'保存失败，请重试！', 403);
                        }
                    }
                }
            }
            if($post_data['update_products_suppliers']) {
                foreach ($post_data['update_products_suppliers'] as $row ) {
                    if(!$row['sku']) continue;
                    $pro_supp_model = '';
                    if($row['id']) {
                        $pro_supp_model = ProductsSuppliers::findOne($row['id']);
                    } else {
                        $pro_supp_model = new ProductsSuppliers();

                    }
                    $pro_supp_model->supplier_id = $row['supplier_id'];
                    $pro_supp_model->sku = $row['sku'];
                    $pro_supp_model->url = $row['url'];
                    $pro_supp_model->min_buy = $row['min_buy'];
                    $pro_supp_model->price = $row['price'];
                    $pro_supp_model->deliver_time = $row['deliver_time'];
//                        $pro_supp_model->setIsNewRecord(true);
                    if($pro_supp_model->save()) {
                    } else {
                        throw new NotFoundHttpException( '新建供应商失败'. print_r($model->getErrors(), true).'保存失败，请重试！', 403);
                    }
                }
            }
            return $this->redirect(['index']);

        }else {
            return $this->render('addSuppliers', [
                'model' => $model,
                'suppliers' => $suppliersData,
                'product_supplier' => $pro_sup
            ]);
        }
    }


}
