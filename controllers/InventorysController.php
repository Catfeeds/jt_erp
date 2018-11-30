<?php

namespace app\controllers;

use app\models\InventorysItems;
use app\models\LocationStock;
use app\models\ProductsVariant;
use app\models\StockLocationCode;
use app\models\User;
use Yii;
use app\models\Inventorys;
use app\models\InventorysSearch;
use yii\base\ErrorException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * InventorysController implements the CRUD actions for Inventorys model.
 */
class InventorysController extends Controller
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
     * Lists all Inventorys models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InventorysSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $user = new User();
        $user_arr = $user->getUsers();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user_arr' => $user_arr,
        ]);
    }

    /**
     * Displays a single Inventorys model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $items =  InventorysItems::findAll(['inventory_id' => $id]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'items' => $items
        ]);
    }

    /**
     * Creates a new Inventorys model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Inventorys();
        $model->inventory_date = date('Y-m-d H:i:s');
        $model->create_uid = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Inventorys model.
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
     * Deletes an existing Inventorys model.
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
     * Finds the Inventorys model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Inventorys the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Inventorys::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAddStock()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);
        $location_code = trim(Yii::$app->request->get('location_code'));
        $sku = trim(Yii::$app->request->get('sku'));
        $inventory_qty = intval(Yii::$app->request->get('stock'));

        $locationCode = StockLocationCode::find()->where(['stock_code' => $model->stock, 'code' => $location_code])->one();
        if(!$locationCode){
            return '库位不存在!';
            exit;
        }
        $product = ProductsVariant::find()->where(['sku' => $sku])->one();
        if(!$product)
        {
            return 'SKU不存在!';
            exit;
        }

        $stockModel = new LocationStock();
        $stock = $stockModel->find()->where([
            'stock_code' => $model->stock,
            'location_code' => $location_code,
            'sku' => $sku
            ])->one();
        $stock_qty = 0;
        if($stock)
        {
            $stock_qty = $stock->stock;
        }
        $difference_qty = $inventory_qty-$stock_qty;
        $itemModel = new InventorysItems();
        $itemModel->attributes = [
            'inventory_id' => $id,
            'location_code' => $location_code,
            'sku' => $sku,
            'inventory_qty' => $inventory_qty,
            'stock_qty' => $stock_qty,
            'difference_qty' => $difference_qty,
        ];
        if($itemModel->save())
        {
            echo 1;
        }else{
            print_r($itemModel->getErrors());
        }
    }

    public function actionDeleteStock($id){
        $model = InventorysItems::findOne($id);
        if($model){
            $model->delete();
        }

    }

    /**
     * 确认
     * @param $id
     * @throws NotFoundHttpException
     */
    public function actionConfirm($id){
        $model = $this->findModel($id);
        $model->order_status = 1;
        $model->save();
    }

    /**
     * 更新库位库存
     * @param $id
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionUpdateStock($id)
    {
        $model = $this->findModel($id);
        //部分盘点
        if ($model->is_all == 1)
        {
            $items =  InventorysItems::find()->where(['inventory_id' => $id])->andWhere(['<>', 'difference_qty', 0])->all();
            $tr = Yii::$app->db->beginTransaction();
            try{
                foreach($items as $item){
                    $location_code = StockLocationCode::find()->where(['stock_code' => $model->stock])->andWhere(['code' => $item->location_code])->one();
                    $area_code = 'A';
                    if($location_code){
                        $area_code = $location_code->area_code;
                    }
                    $location_stock = LocationStock::find()->where(['stock_code' => $model->stock])->andWhere(['location_code' => $item->location_code])->andWhere(['sku' => $item->sku])->one();
                    $location_stock_id = $location_stock->id?$location_stock->id:0;
                    $location_stock_stock = $location_stock->stock?$location_stock->stock:0;
                    if($location_stock)
                    {
                        Yii::$app->db->createCommand("UPDATE location_stock SET stock=stock+:stock WHERE id=:id", [':stock' => $item->difference_qty, ':id' => $location_stock->id])->execute();
                    }else{
                        $in_data = [
                            ':stock_code' => $model->stock,
                            ':area_code' => $area_code,
                            ':location_code' => $item->location_code,
                            ':sku' => $item->sku,
                            ':create_date' => date('Y-m-d H:i:s'),
                            ':stock' => $item->difference_qty
                        ];
                        print_r($in_data);
                        Yii::$app->db->createCommand("INSERT INTO location_stock (stock_code, area_code, location_code, sku, create_date, stock) VALUE (:stock_code, :area_code, :location_code, :sku, :create_date, :stock)", $in_data)->execute();
                        $location_stock_id = Yii::$app->db->getLastInsertID();
                    }
                    //日志
                    Yii::$app->db->createCommand("INSERT INTO location_log (order_id, sku, qty, stock_code, location_code, uid, create_date, `type`, location_stock_id, original_qty) VALUE (:order_id, :sku, :qty, :stock_code, :location_code, :uid, :create_date, :tp, :location_stock_id, :original_qty)", 
                    [':order_id' => $model->id, ':sku' => $item->sku, ':qty' => $item->difference_qty, ':stock_code' => $model->stock, ':location_code' => $item->location_code, ':create_date' => date('Y-m-d H:i:s'), ':tp' => 5, ':uid' => Yii::$app->user->id, ':location_stock_id' => $location_stock_id, ':original_qty' => $location_stock_stock])->execute();

                }
                $model->order_status = 2;
                $model->save();
                $tr->commit();
            }catch (Exception $e){
                $tr->rollBack();
            }
        }
        elseif ($model->is_all == 2)   //全部盘点
        {

            $items =  InventorysItems::find()->where(['inventory_id' => $id])->all();
            $tr = Yii::$app->db->beginTransaction();
            try{
                //全部库区库存更新为0
                Yii::$app->db->createCommand("UPDATE location_stock SET stock=0 WHERE stock_code=:stock_code",[':stock_code'=>$model->stock])->execute();
                foreach ($items as $item)
                {
                    $location_code = StockLocationCode::find()->where(['stock_code' => $model->stock])->andWhere(['code' => $item->location_code])->one();
                    $area_code = 'A';
                    if($location_code)
                    {
                        $area_code = $location_code->area_code;
                    }
                    $location_stock = LocationStock::find()->where(['stock_code' => $model->stock])->andWhere(['location_code' => $item->location_code])->andWhere(['sku' => $item->sku])->one();
                    $location_stock_id = $location_stock->id?$location_stock->id:0;
                    $location_stock_stock = $location_stock->stock?$location_stock->stock:0;
                    if ($location_stock)
                    {
                        Yii::$app->db->createCommand("UPDATE location_stock SET stock=stock+:stock WHERE id=:id", [':stock' => $item->inventory_qty, ':id' => $location_stock->id])->execute();
                    }
                    else
                    {
                        $in_data = [
                            ':stock_code' => $model->stock,
                            ':area_code' => $area_code,
                            ':location_code' => $item->location_code,
                            ':sku' => $item->sku,
                            ':create_date' => date('Y-m-d H:i:s'),
                            ':stock' => $item->difference_qty
                        ];
                        print_r($in_data);
                        Yii::$app->db->createCommand("INSERT INTO location_stock (stock_code, area_code, location_code, sku, create_date, stock) VALUE (:stock_code, :area_code, :location_code, :sku, :create_date, :stock)", $in_data)->execute();
                        $location_stock_id = Yii::$app->db->getLastInsertID();
                    }
                    //日志
                    Yii::$app->db->createCommand("INSERT INTO location_log (order_id, sku, qty, stock_code, location_code, uid, create_date, `type`, location_stock_id, original_qty) VALUE (:order_id, :sku, :qty, :stock_code, :location_code, :uid, :create_date, :tp, :location_stock_id, :original_qty)", 
                    [':order_id' => $model->id, ':sku' => $item->sku, ':qty' => $item->inventory_qty, ':stock_code' => $model->stock, ':location_code' => $item->location_code, ':create_date' => date('Y-m-d H:i:s'), ':tp' => 5, ':uid' => Yii::$app->user->id, ':location_stock_id' => $location_stock_id, ':original_qty' => $location_stock_stock])->execute();
                }
                $model->order_status = 2;
                $model->save();
                $tr->commit();
            }
            catch (Exception $e)
            {
                $tr->rollBack();
            }
        }
    }

    /**
     * 导入盘点表
     * @return string
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function actionImportInventorys()
    {
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstanceByName('inventorysData');

            if (strpos($file->name, ".xlsx") > 0) {
                $PHPReader = new \PHPExcel_Reader_Excel2007();
                $PHPExcel = $PHPReader->load($file->tempName);
                $currentSheet = $PHPExcel->getSheet(0);
                $allRow = $currentSheet->getHighestRow();

                $modifyArr = [];
                $location_stock_list = [];
                $location_code_arr = [];
                for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
                    $record = [];
                    for ($column = 'A'; $column <= 'F'; $column++) {
                        $data = trim($currentSheet->getCell($column . $currentRow)->getValue());
                        array_push($record, $data);
                    }

                    if (!empty($record[0]) && !empty($record[1])) {
                        $location_code_arr[] = strtoupper($record[0]);
//                        $location_stock_list[strtoupper($record[0])][strtoupper($record[1])] = intval($record[2]);
                        $location_stock_list[] = [
                            'location_code' => strtoupper($record[0]),
                            'sku' => strtoupper($record[1]),
                            'stock' => intval($record[2])
                        ];
                    }
                }

                if ($location_code_arr) {
                    $LocationStockModel = new LocationStock();
                    $location_stock_row = $LocationStockModel->find()->where(['in', 'location_code', $location_code_arr])->andWhere(['stock_code' => 'SZ001'])->asArray()->all();
                    $inventorys_list = [];
                    $location_stock_rows = [];
                    if($location_stock_row)
                    {
                        foreach ($location_stock_row as $row) {
                            $location_stock_rows[$row['location_code']][$row['sku']] += $row['stock'];
                        }
                    }

                    if ($location_stock_list) {
                        $stock_code = 'SZ001';
                        $inventorys_list[$stock_code]['stock'] = $stock_code;
                        foreach ($location_stock_list as $row) {
                            $location_code = strtoupper($row['location_code']);
                            $sku = strtoupper($row['sku']);
                            $item['sku'] = $sku;
                            $item['location_code'] = $location_code;
                            $item['inventory_qty'] = $row['stock'];
                            $item['stock_qty'] = isset($location_stock_rows[$location_code][$sku]) ? $location_stock_rows[$location_code][$sku] : 0;
                            $item['difference_qty'] = (int)$item['inventory_qty'] - $item['stock_qty'];
                            $inventorys_list[$stock_code]['item'][] = $item;
                        }

//                        foreach ($location_stock_row as $row) {
//                            $location_code = strtoupper($row['location_code']);
//                            $sku = strtoupper($row['sku']);
//                            if ($location_stock_list[$location_code][$sku]) {
//                                $inventorys_list[$row['stock_code']]['stock'] = $row['stock_code'];
//                                $item['sku'] = $sku;
//                                $item['location_code'] = $location_code;
//                                $item['inventory_qty'] = $location_stock_list[$location_code][$sku];
//                                $item['stock_qty'] = $row['stock'];
//                                $item['difference_qty'] = (int)$item['inventory_qty'] - $item['stock_qty'];
//                                $inventorys_list[$row['stock_code']]['item'][] = $item;
//                            }
//                        }
                        $time = date('Y-m-d H:i:s');
                        foreach ($inventorys_list as $row) {
                            $inventorysModel = new Inventorys();
                            $inventorysModel->stock = $row['stock'];
                            $inventorysModel->inventory_date = $time;
                            $inventorysModel->create_time = $time;
                            $inventorysModel->create_uid = Yii::$app->user->getId();
                            if ($inventorysModel->save()) {
                                $inventorysItemsModel = new InventorysItems();
                                foreach ($row['item'] as $v) {
                                    $inventorysItemsModel->setIsNewRecord(true);
                                    unset($inventorysItemsModel->id);
                                    $inventorysItemsModel->inventory_id = $inventorysModel->id;
                                    $inventorysItemsModel->location_code = $v['location_code'];
                                    $inventorysItemsModel->sku = $v['sku'];
                                    $inventorysItemsModel->inventory_qty = $v['inventory_qty'];
                                    $inventorysItemsModel->stock_qty = $v['stock_qty'];
                                    $inventorysItemsModel->difference_qty = $v['difference_qty'];
                                    if ($inventorysItemsModel->save()) {
                                        array_push($modifyArr, '库位编号: ' . $v['location_code'] . ' sku: ' . $v['sku'] . " <font color='#00ff00'>盘存单导入成功</font><br>");
                                    } else {
                                        array_push($modifyArr, '库位编号: ' . $v['location_code'] . ' sku: ' . $v['sku'] . " <font color='#ff0000'>盘存单导入失败</font><br>");
                                    }
                                }
                            }else{
                                print_r($inventorysModel->getErrors());
                            }
                        }

                    }
                    $notice = implode("", $modifyArr);
                }

            } else {
                $notice = '文件格式错误，请上传xlsx格式文件';
            }
        }
        return $this->render("import_inventory", ["notice" => $notice]);

    }

    /**
     * 全盘单导入
     * @return string
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function actionImportInventoryAll()
    {
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstanceByName('inventorysData');

            if (strpos($file->name, ".xlsx") > 0) {
                $PHPReader = new \PHPExcel_Reader_Excel2007();
                $PHPExcel = $PHPReader->load($file->tempName);
                $currentSheet = $PHPExcel->getSheet(0);
                $allRow = $currentSheet->getHighestRow();

                $modifyArr = [];
                $location_stock_list = [];
                $location_code_arr = [];
                for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
                    $record = [];
                    for ($column = 'A'; $column <= 'F'; $column++) {
                        $data = trim($currentSheet->getCell($column . $currentRow)->getValue());
                        array_push($record, $data);
                    }

                    if (!empty($record[0]) && !empty($record[1])) {
                        $location_code_arr[] = strtoupper($record[0]);
//                        $location_stock_list[strtoupper($record[0])][strtoupper($record[1])] = intval($record[2]);
                        $location_stock_list[] = [
                            'location_code' => strtoupper($record[0]),
                            'sku' => strtoupper($record[1]),
                            'stock' => intval($record[2])
                        ];
                    }
                }

                if ($location_code_arr) {
                    $LocationStockModel = new LocationStock();
                    $location_stock_row = $LocationStockModel->find()->where(['in', 'location_code', $location_code_arr])->andWhere(['stock_code' => 'SZ001'])->asArray()->all();
                    $inventorys_list = [];
                    $location_stock_rows = [];
                    if($location_stock_row)
                    {
                        foreach ($location_stock_row as $row) {
                            $location_stock_rows[$row['location_code']][$row['sku']] += $row['stock'];
                        }
                    }

                    if ($location_stock_list) {
                        $stock_code = 'SZ001';
                        $inventorys_list[$stock_code]['stock'] = $stock_code;
                        foreach ($location_stock_list as $row) {
                            $location_code = strtoupper($row['location_code']);
                            $sku = strtoupper($row['sku']);
                            $item['sku'] = $sku;
                            $item['location_code'] = $location_code;
                            $item['inventory_qty'] = $row['stock'];
                            $item['stock_qty'] = isset($location_stock_rows[$location_code][$sku]) ? $location_stock_rows[$location_code][$sku] : 0;
                            $item['difference_qty'] = (int)$item['inventory_qty'] - $item['stock_qty'];
                            $inventorys_list[$stock_code]['item'][] = $item;
                        }

                        $time = date('Y-m-d H:i:s');
                        foreach ($inventorys_list as $row) {
                            $inventorysModel = new Inventorys();
                            $inventorysModel->stock = $row['stock'];
                            $inventorysModel->inventory_date = $time;
                            $inventorysModel->create_time = $time;
                            $inventorysModel->is_all = 2;   //全盘
                            $inventorysModel->create_uid = Yii::$app->user->getId();
                            if ($inventorysModel->save()) {
                                $inventorysItemsModel = new InventorysItems();
                                foreach ($row['item'] as $v) {
                                    $inventorysItemsModel->setIsNewRecord(true);
                                    unset($inventorysItemsModel->id);
                                    $inventorysItemsModel->inventory_id = $inventorysModel->id;
                                    $inventorysItemsModel->location_code = $v['location_code'];
                                    $inventorysItemsModel->sku = $v['sku'];
                                    $inventorysItemsModel->inventory_qty = $v['inventory_qty'];
                                    $inventorysItemsModel->stock_qty = $v['stock_qty'];
                                    $inventorysItemsModel->difference_qty = $v['difference_qty'];
                                    if ($inventorysItemsModel->save()) {
                                        array_push($modifyArr, '库位编号: ' . $v['location_code'] . ' sku: ' . $v['sku'] . " <font color='#00ff00'>盘存单导入成功</font><br>");
                                    } else {
                                        array_push($modifyArr, '库位编号: ' . $v['location_code'] . ' sku: ' . $v['sku'] . " <font color='#ff0000'>盘存单导入失败</font><br>");
                                    }
                                }
                            }else{
                                print_r($inventorysModel->getErrors());
                            }
                        }

                    }
                    $notice = implode("", $modifyArr);
                }

            } else {
                $notice = '文件格式错误，请上传xlsx格式文件';
            }
        }
        return $this->render("import_inventory_all", ["notice" => $notice]);

    }


}
