<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class AgendaController extends ActiveController
{
    public $modelClass = 'common\models\Agenda';

    public function actionTrocasporuser($userid){
        $model = $this->modelClass;

        return $model::find()
            ->where(['user_id' => $userid])
            ->with('user')
            ->asArray()
            ->all();
    }

}
