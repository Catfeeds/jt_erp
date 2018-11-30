<?php

namespace app\controllers;

use app\models\WebsitesGroup;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use app\models\Websites;
use app\models\WebsitesBaseSearch;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;


/**
 * WebsitesController implements the CRUD actions for Websites model.
 */
class WebsitesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'update', 'view', 'delete', 'upload'],
                'rules' => [
                    // 允许认证用户
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // 默认禁止其他用户
                ],
            ],
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
     * 首图上伟
     * @return string
     */
    public function actionImageUpload()
    {
        $model = new Websites();

        $imageFile = UploadedFile::getInstance($model, Yii::$app->request->get('attribute', 'image'));

        $directory = Yii::getAlias('@imgDir') . DIRECTORY_SEPARATOR . date('Ymd') . DIRECTORY_SEPARATOR;

        if (!is_dir($directory)) {
            FileHelper::createDirectory($directory);
        }

        if ($imageFile) {
//            $uid = uniqid(time(), true);
//            $fileName = $uid . '.' . $imageFile->extension;
//            $filePath = $directory . $fileName;
//            if ($imageFile->saveAs($filePath)) {
//                $path = Yii::getAlias('@imgPath').'/'. date('Ymd') . '/' . $fileName;
//                return Json::encode([
//                    'files' => [
//                        [
//                            'name' => $fileName,
//                            'size' => $imageFile->size,
//                            'url' => $path,
//                            'thumbnailUrl' => $path,
//                            'deleteUrl' => 'image-delete?name=' . $fileName,
//                            'deleteType' => 'POST',
//                        ],
//                    ],
//                ]);
//            }

            $uid = uniqid(time(), true);
            $fileName = $uid . '.' . $imageFile->extension;
            $storage = Yii::$app->get('storage');
            $fdfsAdapter = $storage->getDisk('fdfs')->getAdapter();
            try {
                $format = substr($imageFile->type, strpos($imageFile->type, '/') + 1);
                $resJson = $fdfsAdapter->uploadFile([$imageFile->tempName], $format);
                $res = json_decode($resJson, true);
                if ($res['status'] == 0) {
                    $fullName = 'http://cdn.kingdomskymall.net/' . $res['path'];
                    return Json::encode([
                        'files' => [
                            [
                                'name' => $res['path'],
                                'size' => $imageFile->size,
                                'url' => $fullName,
                                'thumbnailUrl' => $fullName,
                                'deleteUrl' => 'image-delete?name=' . $res['path'],
                                'deleteType' => 'POST',
                            ],
                        ],
                    ]);
                } else {
                    return $res['msg'];
                }
            } catch (\Exception $e) {
                return 'file upload failed';
            }

        }

        return '';
    }

    /**
     * Lists all Websites models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WebsitesBaseSearch();
        $param = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($param);

        //获取开发人员信息
        $websiteModel = new Websites();
        $userMedel = new User();
        $user_arr = $websiteModel->find()->all();
        $user_arr = array_unique(array_column($user_arr,'uid'));
        $user_all = User::find()->select('id,username')->where(['in','id',$user_arr])->indexBy('id')->all();
        $user_arr_new = array();
        foreach ($user_all as $uid => $user)
        {
            $user_arr_new[$uid] = $user['username'];
        }
        $orderTimeBegin = "";
        $orderTimeEnd = "";
        $domain_host = '';
        $uid = '';
        if ($param['order_time_begin'])
        {
            $orderTimeBegin = $param['order_time_begin'];
        }
        if ($param['order_time_end'])
        {
            $orderTimeEnd = $param['order_time_end'];
        }
        if ($param['domain_host'])
        {
            $domain_host = $param['domain_host'];
        }
        if($param['uid'])
        {
            $uid = $param['uid'];
        }

        $is_admin = $userMedel->getGroupUsers();
        $is_select = 0;
        if($is_admin['is_select'] == 1) {
            $is_select = 1;
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'is_select' => $is_select,
            'orderTimeBegin' => $orderTimeBegin,
            'orderTimeEnd' => $orderTimeEnd,
            'domain_host' => $domain_host,
            'user_arr' => $user_arr_new,
            'uid' => $uid,
        ]);
    }

    /**
     * Displays a single Websites model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $userMedel = new \app\models\User();
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
     * Creates a new Websites model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Websites();
        $model->spu = Yii::$app->request->get('spu');
        if(empty($model->spu))
        {
            throw new NotFoundHttpException('请选择对应产品发布站点！', 403);
        }

        $products = $_POST;
        if(!empty($products['Websites']['title']))
        {
            $products['Websites']['images'] = json_encode($_POST['image_val']);
            $product_styles = [];
            if(isset($_POST['style_image']))
            {
                foreach ($_POST['style_image'] as $key => $style_image) {
                    if($_POST['style_name'][$key]){
                        $product_styles[] = [
                            'image' => $style_image,
                            'name' => $_POST['style_name'][$key],
                            'add_price' => (float)$_POST['style_price'][$key],
                            'color' => $_POST['style_color'][$key],
                        ];
                    }

                }
            }

            if ($product_styles) {
                $products['Websites']['product_style'] = json_encode($product_styles);
            }

        }

        $model->uid = Yii::$app->user->id;

        $model->sale_end_hours = 6;

        if ($model->load($products) && $model->save()) {
            //套餐数据
            $groupModel = new WebsitesGroup();
            $groupModel->saveWebsiteGroup($model->id, $_POST);
            return $this->redirect(['/websites-sku/index', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Websites model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $products = $_POST;

        if(!empty($products['Websites']['title']))
        {
            $products['Websites']['images'] = json_encode($_POST['image_val']);
            $product_styles = [];
            if(isset($_POST['style_image']))
            {
                foreach ($_POST['style_image'] as $key => $style_image) {
                    if($_POST['style_name'][$key]){
                        $product_styles[] = [
                            'image' => $style_image,
                            'name' => $_POST['style_name'][$key],
                            'add_price' => (float)$_POST['style_price'][$key],
//                            'color' => $_POST['style_color'][$key],
                        ];
                    }

                }
            }

            if ($product_styles) {
                $products['Websites']['product_style'] = json_encode($product_styles);
            }

        }

        if ($model->load($products) && $model->save()) {
            //套餐数据
            $groupModel = new WebsitesGroup();
            $groupModel->saveWebsiteGroup($model->id, $_POST);
            return $this->redirect(['/websites-sku/index', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 站点复制
     */
    public function actionCopy()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);
        unset($model->id);
        $model->setIsNewRecord(true);
        $model->host = uniqid();
        $model->uid = Yii::$app->user->id;
        $model->create_time = date('Y-m-d H:i:s');
        $model->update_time = date('Y-m-d H:i:s');
        if($model->save())
        {
            $new_id = $model->id;
            $websiteGroup = WebsitesGroup::findAll(['website_id' => $id]);
            foreach($websiteGroup as $web_group)
            {
                $web_group->setIsNewRecord(true);
                unset($web_group->id);
                $web_group->website_id = $new_id;

                $web_group->save();

            }
            return $this->redirect(['/websites/update', 'id' => $model->id]);
        }else{
            print_r($model->getErrors());
        }
    }

    /**
     * Deletes an existing Websites model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        throw new NotFoundHttpException('站点不能删除');
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Websites model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Websites the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Websites::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
