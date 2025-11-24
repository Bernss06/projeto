<?php

namespace backend\modules\api\controllers;
use Yii;
use yii\rest\ActiveController;

class ItemController extends ActiveController
{
    public $modelClass = 'common\models\Item';

    //public function actionDebug()
    //{
    //    try {
    //        $data = Yii::$app->request->post();
    //        return ['received' => $data];
    //    } catch (\Throwable $e) {
    //        return [
    //            'error' => $e->getMessage(),
    //            'file' => $e->getFile(),
    //            'line' => $e->getLine()
    //        ];
    //    }
    //}

}
