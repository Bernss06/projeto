<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class GostoController extends ActiveController
{
    public $modelClass = 'common\models\Gosto';

    // Contagem de Gostos
    public function actionCount(){
        $model = new $this->modelClass;
        $recs = $model::find()->all();
        return ['count'=>count($recs)];
    }
}
