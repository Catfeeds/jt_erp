<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\LocationStock;
use app\models\Orders;
use app\models\OrdersItem;
use app\models\PurchasesItems;
use app\models\SkuBoxs;
use app\models\Stocks;
use yii\console\Controller;
use yii\console\ExitCode;
use Yii;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{

    /**
     * 更新库存SKU为对应的主SKU
     */
    public function actionUpdateStockSku()
    {
        $model = new Stocks();
        $boxModel = new SkuBoxs();
        $stocks = $model->find()->all();
        foreach ($stocks as $stock)
        {
            $sku_box = $boxModel->getSkuBys($stock->sku);
            $stock->sku = $sku_box;
            echo $stock->save();
        }
    }

    /**
     * 更新为位库存SKU
     */
    public function actionUpdateLocationSku()
    {
        $model = new LocationStock();
        $boxModel = new SkuBoxs();
        $datas = $model->find()->all();
        foreach ($datas as $data)
        {
            $sku_box = $boxModel->getSkuBys($data->sku);
            $data->sku = $sku_box;
            echo $data->save();
        }
    }

    /**
     * 更新订单SKU
     * @throws \yii\db\Exception
     */
    public function actionUpdateOrderSku()
    {
        $skuBox = new SkuBoxs();
        $sql = "SELECT B.id, B.sku FROM orders AS A LEFT JOIN orders_item AS B ON B.order_id=A.id WHERE A.status IN (1,2,3,7,8,20,21)";
        $datas = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($datas as $data)
        {
            $sku = $skuBox->getSkuBys($data['sku']);
            if($sku)
            {
                $item = OrdersItem::findOne($data['id']);
                echo $sku."\n";
                $item->sku = $sku;
                echo $item->save();
            }

        }
    }

    /**
     * 修改采购SKU
     */
    public function actionUpdatePurSku()
    {
        $skuBox = new SkuBoxs();
        $sql = "SELECT id,sku FROM purchase_items";
        $datas = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($datas as $data)
        {
            $sku = $skuBox->getSkuBys($data['sku']);
            if($sku)
            {
                $item = PurchasesItems::findOne($data['id']);
                echo $sku."\n";
                $item->sku = $sku;
                echo $item->save();
            }

        }
    }

}
