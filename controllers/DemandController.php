<?php

namespace app\controllers;

use yii\web\Controller;


class DemandController extends Controller
{
    public function actionIndex()
    {
        return $this->render("index",[
        ]);
    }

    public function actionDemandPool()
    {
        return $this->render("demand_pool",[
        ]);
    }
}
