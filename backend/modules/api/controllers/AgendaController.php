<?php

namespace backend\modules\api\controllers;

use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class AgendaController extends ActiveController
{
    public $modelClass = 'common\models\Troca';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
            'tokenParam' => 'token',
        ];

        return $behaviors;
    }

    public function actionTrocasporuser($userid){
        $model = $this->modelClass;

        return $model::find()
            ->where(['user_id' => $userid])
            ->with('user')
            ->asArray()
            ->all();
    }

}
