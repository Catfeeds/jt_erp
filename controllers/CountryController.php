<?php

namespace app\controllers;

use app\models\ShipingArea;
use Yii;
use yii\web\Controller;


class CountryController extends Controller
{
    /**
     * 获取省
     */
    public function actionGetProvince()
    {
        $country = Yii::$app->request->get('country');
        $model = new ShipingArea();
        $data = $model->find()->where(['country' => $country,'status' => 1])->groupBy('province')->all();
        $province = [];
        foreach($data as $v)
        {
            $province[] = $v->province;
        }
        echo json_encode($province);
        exit;
    }

    /**
     * 获取市
     */
    public function actionGetCity()
    {
        $country = Yii::$app->request->get('country');
        $province = Yii::$app->request->get('province');
        $model = new ShipingArea();
        $data = $model->find()->where(['country' => $country, 'province' => $province,'status' => 1])->groupBy('city')->all();
        $citys = [];
        foreach($data as $v)
        {
            $citys[] = [
                'city'      => $v->city,
                'post_code' => $v->post_code
            ];
        }
        echo json_encode($citys);
        exit();
    }

    /**
     * 获取区
     */
    public function actionGetArea()
    {
        $country = Yii::$app->request->get('country');
        $province = Yii::$app->request->get('province');
        $city = Yii::$app->request->get('city');
        $model = new ShipingArea();
        $data = $model->find()->where(['country' => $country, 'province' => $province, 'city' => $city])->all();
        $areas = [];
        foreach($data as $v)
        {
            $areas[] = [
                'area'      => $v->area,
                'post_code' => $v->post_code
            ];
        }
        echo json_encode($areas);
        exit();
    }

}
