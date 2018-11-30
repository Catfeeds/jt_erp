<?php

namespace app\controllers;

use app\models\ProductComment;
use app\models\ShipingArea;
use app\models\SkuBoxs;
use app\models\Websites;
use app\models\WebsitesGroup;
use Yii;
use app\models\Categories;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\base\Theme;
use app\models\WebsitesBaseSearch;
use app\models\ProductsBase;
use app\models\WebsitesSku;
use app\models\Orders;
use app\models\OrdersItem;
use app\models\ProductsVariant;
use app\models\FraudFilterDetector;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class ShopController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * Lists all Categories models.
     * @return mixed
     */
    public function actionIndex()
    {
        $host = Yii::$app->request->get('host', 0);
        if (empty($host)) {
            //$this->redirect(['/']);
            //return false;
        }
        $website = Websites::findOne(['host' => $host]);

        Yii::$app->getView()->theme = new Theme([
            "basePath" => '@app/themes/' . $website->theme,
            "pathMap" => [
                '@app/views' => '@app/themes/' . $website->theme,
            ],
        ]);
        $commentModel = new ProductComment();
        $comment = $commentModel->find()->where(['website_id' => $website->id,'isshow'=>1])->asArray()->all();
        foreach ($comment as $key => $value) {
            $comment[$key]['phone'] = substr($value['phone'],0,strlen($value['phone'])-4).'****';
            $comment[$key]['add_time'] = date('Y-m-d',strtotime($value['add_time']));
        }
        #$comment = ProductComment::findAll(['website_id' => $website->id]);
        return $this->renderPartial('/index', [
            "requestId" => $host,
            'website' => $website,
            'comment' => $comment
            ]);
    }

    /**
     * Lists all Categories models.
     * @return mixed
     */
    public function actionApi()
    {
        $host = Yii::$app->request->get('host', 0);
        if (empty($host)) {
            //$this->redirect(['/']);
            //return false;
        }

        $websiteSeachModel = new WebsitesBaseSearch();
        $res = $websiteSeachModel->find()->where(['host' => $host])->one();
        //CLOAK处理
        if($res->cloak==1 && $_GET['a']!='debug')
        {
            $fraudFilterDetector = new FraudFilterDetector();
            $boolean = $fraudFilterDetector->check();

            // 根据我们返回的这个$boolean变量来决定是显示正品还是显示仿品
            if(!$boolean){

            $sps = strpos(strtolower($res->cloak_url), $_SERVER['HTTP_HOST']);
            if($sps || empty($res->cloak_url)){
                exit;
            }
            $urls = parse_url($res->cloak_url);
            $paths = explode('/', $urls['path']);
//            print_r($paths[2]);
            $res = $websiteSeachModel->find()->where(['host' => array_pop($paths)])->one();

            }
        }

        // 获取下单数的缓存
        $cache = Yii::$app->cache;
        $key = "order_count_" . $host;
        $orderCount = $cache->get($key);
        if (empty($orderCount))
        {
            $orderCount = mt_rand(2000,5000);
            
        }
        $cache->set($key, $orderCount+1);

        // 处理SIZE数据
        $sizeData = [];
        $sizeSupplement = [];
        if(trim($res->size))
        {
            $sizeInfo = explode(',', trim($res->size));
            foreach ($sizeInfo as $size)
            {
                $sizeTemp = explode("#", $size);
                $sizeData[] = $sizeTemp[0];
                if (isset($sizeTemp[1]))
                {
                    $sizeSupplement[$sizeTemp[0]] = $sizeTemp[1];
                }
            }
        }


        // 处理COLOR数据
        $colorSupplement = [];
        if($res->product_style)
        {
            $colorInfo = json_decode($res->product_style, true);

            foreach ($colorInfo as $color)
            {
                $colorSupplement[$color['name']] = $color['add_price'];
            }
        }


        $product = [];
        $product['title'] = $res->title;
        $product['sale_price'] = intval($res->sale_price);
        $product['next_price'] = intval($res->next_price);
        $product['price'] = intval($res->price);
        $product['sale_end_hours'] = $res->sale_end_hours;
        $product['images'] = json_decode($res->images);
        $product['discount'] = (round(($res->price-$res->sale_price) / $res->price, 2)*100).'%';
        $product['save'] = $res->price - $res->sale_price;
        $product['info'] = $res->info;
        $product['sale_info'] = $res->sale_info;
        $product['additional'] = $res->additional;
        $product['is_group'] = $res->is_group;
        $product['groups'] = [];
        if($res->is_group)
        {
            $groups = WebsitesGroup::findAll(['website_id' => $res->id]);
            foreach($groups as $group)
            {
                $group_array = [
                    'group_id' => $group->id,
                    'group_title' => $group->group_title,
                    'group_price' => intval($group->group_price),
                    'websites' => [],
                ];
                $ids = explode(',', preg_replace('/\D/i', ',', $group->website_ids));
                $group_websites = [];
                foreach ($ids as $id)
                {
                    $id = intval($id);
                    $group_websites[] = Yii::$app->db->createCommand("SELECT * FROM websites WHERE id={$id}")->queryOne();
                }

                foreach($group_websites as  $wb){
                    $sizes = [];
                    if($wb['size'])
                    {
                        $sizes = explode(',', $wb['size']);

                    }
                    $colors = [];
                    if($wb['product_style'])
                    {
                        $colors = json_decode($wb['product_style']);
                    }
                    $group_array['websites'][] = [
                        'title' => $wb['title'],
                        'website_id' => $wb['id'],
                        'size' => $sizes,
                        'color' => $colors
                    ];
                }
                $product['groups'][] = $group_array;

            }
        }

        echo(json_encode([
            'host' => $host,
            'product' => $product,
            'orderCount' => $orderCount,
            'sizeData' => $sizeData,
            'sizeSupplement' => $sizeSupplement,
            'colorSupplement' => $colorSupplement,
            'colorInfo' => $colorInfo,
        ]));
        exit;
    }

    public function actionAddOrder()
    {
        $param = Yii::$app->request->post();
        $skuBox = new SkuBoxs();
        
        $host = $param['host'];
        $color = $param['color'];
        $size = $param['size'];
        $num = $param['num'];
        $name = $param['name'];
        $mobile = $param['mobile'];
        $email = $param['email'];
        $province = $param['province'];
        $city = $param['city'];
        $area = $param['area'];
        $address = $param['address'];
        $postCode = $param['post_code'];
        $comment = $param['comment'];
        $totalPrice = $param['total_price'];
        if (isset($param['group_id']) && isset($param['is_group']) && isset($param['propertyInfo']))
        {
            $isGroup = $param['is_group'];
            $groupId = $param['group_id'];
            $propertyData = json_decode($param['propertyInfo'], true);
        }
        else
        {
            $isGroup = 0;
            $groupId = 0;
            $propertyData = null;
        }

        $ip = Yii::$app->request->getRemoteIP();
        $cache = Yii::$app->cache;
        $key = "order_filter_".$host."_".$mobile."_".$ip;
        $jsonData = json_decode($cache->get($key), true);
        $cacheTime = time() - 300;
        if (!empty($jsonData) && $jsonData['host'] == $host && $jsonData['mobile'] == $mobile && $jsonData['ip'] == $ip && $jsonData['time'] > $cacheTime)
        {
            //重复订单获取id_order
            $id_order_old = Yii::$app->getDb()->createCommand("select id from orders where mobile = '".$mobile."' and ip = '".$ip."' order by id desc")->queryOne();
            return json_encode([
                'orderId' => $id_order_old['id'],
                'total' => $totalPrice
            ]);
        }
        
        $jsonData = [
            'host' => $host,
            'mobile' => $mobile,
            'ip' => $ip,
            'time' => time(),
        ];

        $cache->set($key, json_encode($jsonData));

        $productsBaseModel = new ProductsBase();
        $websiteSeachModel = new WebsitesBaseSearch();
        $websiteSkuModel = new WebsitesSku();
        $produceVariant = new ProductsVariant();
        
        // 查WEBSITE信息
        $websiteBaseInfo = $websiteSeachModel->find()->where(['host' => $host])->one();
        $product = $websiteBaseInfo->title;

        // 查SPU信息
        $productBaseInfo = $productsBaseModel->find()->where(['spu' =>  $websiteBaseInfo->spu])->one();

        // 保存定单
        $ordersModel = new Orders();
        $ordersItemModel = new OrdersItem();

        $ordersModel->setIsNewRecord(true);
        $ordersModel->attributes = [
            'website_id' => $websiteBaseInfo->id,
            'product' => $product,
            'name' => $name,
            'mobile' => trim($mobile),
            'email' => $email,
            'country' => $websiteBaseInfo->sale_city,
            'district' => $province,
            'city' => $city,
            'area' => $area,
            'address' => $address,
            'post_code' => trim($postCode),
            'pay' => 'COD',
            'comment' => $comment,
            'qty' => $num,
            'total' => $totalPrice,
            'ip' => Yii::$app->request->getRemoteIP(),
            'channel_type' => $productBaseInfo->product_type,
            'uid' => $websiteBaseInfo->uid,
            'channel_type' => 'P'
        ];

        if($ordersModel->save())
        {
            //默认订单号为运单号
            Yii::$app->db->createCommand("update orders set order_no = ".$ordersModel->id." where id = ".$ordersModel->id)->execute();
            if (empty($isGroup))
            {
                if(is_array($color) || is_array($size))
                {
                    foreach($color as $key=>$c)
                    {
                        $c = trim($c);
                        // 查SKU信息
                        $websiteSkuInfo = $websiteSkuModel->find()->where(['website_id' => $websiteBaseInfo->id]);
                        if($c)
                        {
                            $websiteSkuInfo->andWhere(['color' => $c]);
                        }
                        if($size[$key])
                        {
                            $size[$key] = trim($size[$key]);
                            $websiteSkuInfo->andWhere(['size' => $size[$key]]);
                        }
                        $websiteSkuInfo = $websiteSkuInfo->one();

                        $sku = $websiteSkuInfo->sku;
                        $sku = $skuBox->getSkuBys($sku);

                        // 查varant
                        $variant = $produceVariant->find()->where(['sku' => $sku])->one();

                        // 保存order item
                        $ordersItemModel->setIsNewRecord(true);
                        unset($ordersItemModel->id);
                        $ordersItemModel->attributes = [
                            'order_id' => $ordersModel->id,
                            'sku' => $sku,
                            'qty' => $num,
                            'price' => $websiteBaseInfo->sale_price,
                            'color' => $c,
                            'size' => $size[$key],
                            'image' => $variant->image,
                        ];
                        $ordersItemModel->save();
                    }
                }else{
                    $color = trim($color);
                    $size = trim($size);
                    // 查SKU信息
                    $websiteSkuInfo = $websiteSkuModel->find()->where(['website_id' => $websiteBaseInfo->id]);
                    if($color)
                    {
                        $websiteSkuInfo->andWhere(['color' => $color]);
                    }
                    if($size)
                    {
                        $websiteSkuInfo->andWhere(['size' => $size]);
                    }
                    $websiteSkuInfo = $websiteSkuInfo->one();

                    $sku = $websiteSkuInfo->sku;
                    $sku = $skuBox->getSkuBys($sku);

                    // 查varant
                    $variant = $produceVariant->find()->where(['sku' => $sku])->one();

                    // 保存order item
                    $ordersItemModel->setIsNewRecord(true);
                    $ordersItemModel->attributes = [
                        'order_id' => $ordersModel->id,
                        'sku' => $sku,
                        'qty' => $num,
                        'price' => $websiteBaseInfo->sale_price,
                        'color' => $color,
                        'size' => $size,
                        'image' => $variant->image,
                    ];
                    $ordersItemModel->save();
                }
            }
            else
            {
                $groupInfo = WebsitesGroup::find()->where(['id' => $groupId])->one();
                $price = $groupInfo->group_price;
                foreach ($propertyData as $pData)
                {
                    // 查SKU信息
                    $websiteSkuInfo = $websiteSkuModel->find()->andFilterWhere([
                        'website_id' => $pData['website_id'],
                        'size'  => trim($pData['size']),
                        'color' => trim($pData['color'])
                    ])->one();

                    $sku = $websiteSkuInfo->sku;
                        $sku = $skuBox->getSkuBys($sku);

                    // 查varant
                    $variant = $produceVariant->find()->where(['sku' => $sku])->one();

                    // 保存order item
                    $ordersItemModel->setIsNewRecord(true);
                    unset($ordersItemModel->id);
                    $ordersItemModel->attributes = [
                        'order_id' => $ordersModel->id,
                        'sku' => $sku,
                        'qty' => $num,
                        'price' => $price,
                        'color' => $pData['color'],
                        'size' => $pData['size'],
                        'image' => $variant->image,
                    ];
                    if(!$ordersItemModel->save()){
                        print_r($ordersItemModel->getErrors());
                    }
                    $price = 0;
                }
            }
            
            return json_encode([
                'orderId' => $ordersModel->id,
                'total' => $totalPrice
            ]);


        }else{
            print_r($ordersModel->getErrors());
        }



    }


}
