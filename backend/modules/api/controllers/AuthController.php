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
        $model = new \common\models\LoginForm();
        $model->load(Yii::$app->request->post(), '');

        if ($model->login()) {
            $user = $model->getUser();

            // --- CORREÇÃO: Código limpo ---
            // 1. Removemos o $user->save(false) para não dar erro 500.
            // 2. Enviamos o user_id e a auth_key.
            return [
                'status' => 'sucesso',
                'user_id' => $user->id,
                'username' => $user->username,
                'auth_key' => $user->auth_key, 
            ];
        } else {
            // --- CORREÇÃO: O Else que faltava ---
            // Sem isto, se a senha estiver errada, a app crasha.
            Yii::$app->response->statusCode = 401;
            return ['message' => 'Credenciais incorretas'];
        }
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