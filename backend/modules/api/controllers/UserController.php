<?php
namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBasicAuth;
use Yii;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';

    // Contagem de Users no Total
    public function actionCount(){
        $model = new $this->modelClass;
        $recs = $model::find()->all();
        return ['count'=>count($recs)];
    }


    // Autenticação - Basic Auth
    /*public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
            'auth' => [$this, 'auth'],
        ];
        return $behaviors;
    }

    public function auth($username, $password)
    {
        $user = \common\models\User::findByUsername($username);
        if ($user && $user->validatePassword($password)) {
            return $user;
        }
        throw new \yii\web\ForbiddenHttpException('Credenciais inválidas');
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        // Verifica se o user está autenticado
        $user = Yii::$app->user->identity;

        if (!$user) {
            throw new \yii\web\UnauthorizedHttpException('Utilizador não autenticado');
        }

        // Usa RBAC para permitir apenas quem for "admin"
        if (!Yii::$app->user->can('admin')) {
            throw new \yii\web\ForbiddenHttpException('Acesso restrito a administradores');
        }
    }*/
    // Autenticação - Basic Auth
}
