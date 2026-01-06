<?php

namespace backend\modules\api\controllers;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class ItemController extends ActiveController
{
    public $modelClass = 'common\models\Item';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
            'tokenParam' => 'token',
        ];

        return $behaviors;
    }

}
