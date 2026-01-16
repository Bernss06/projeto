<?php

namespace backend\modules\api\controllers;

use yii\rest\Controller;
use Yii;
use backend\modules\api\models\LoginForm;
use backend\modules\api\models\RegisterForm;

class AuthController extends Controller
{
    public function actionLogin()
    {
        $model = new LoginForm();
        $model->load(Yii::$app->request->post(), '');

        if (!$model->validate()) {
            Yii::$app->response->statusCode = 401;
            return $model->errors;
        }

        return [
            'message' => 'Login efetuado com sucesso',
            'user_id' => $model->getUser()->id
        ];
    }

    public function actionLogout()
    {
        return [
            'message' => 'Logout realizado com sucesso'
        ];
    }

    public function actionRegister()
    {
        $model = new RegisterForm();
        $model->load(Yii::$app->request->post(), '');

        $user = $model->register();

        if (!$user) {
            Yii::$app->response->statusCode = 422;
            return $model->errors;
        }

        // No teu AuthController.php, na actionLogin:

        if ($model->login()) {
            $user = $model->getUser(); // Pega no objeto User
            return [
                'user_id' => $user->id,
                'username' => $user->username,
                // ESTA LINHA É OBRIGATÓRIA:
                'auth_key' => $user->auth_key, 
            ];
        }
        
    }
}
