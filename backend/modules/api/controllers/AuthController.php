<?php

namespace backend\modules\api\controllers;

use yii\rest\Controller;
use Yii;
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

        $user = $model->getUser();

        // gera novo token a cada login
        $user->generateAuthKey();
        $user->save(false);

        return [
            'message' => 'Login efetuado com sucesso',
            'user_id' => $user->id,
            'username' => $user->username,
            'auth_key' => $user->auth_key
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

        return [
            'message' => 'Usuário registrado com sucesso',
            'user_id' => $user->id
        ];
    }
}
