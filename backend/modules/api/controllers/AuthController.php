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
    // Cria o modelo de login (que valida user/pass)
    $model = new \common\models\LoginForm();
    
    // Carrega os dados que vieram do Android (POST)
    $model->load(Yii::$app->request->post(), '');

    if ($model->login()) {
        // Pega no utilizador que acabou de logar
        $user = $model->getUser();
        
        // --- AQUI ESTÁ A MAGIA ---
        return [
            'status' => 'sucesso',
            'message' => 'Login efetuado com sucesso',
            'user_id' => $user->id,
            'username' => $user->username,
            // OBRIGATÓRIO: Enviar a chave
            'auth_key' => $user->auth_key, 
        ];
    } else {
        // Se falhar
        Yii::$app->response->statusCode = 401;
        return [
            'status' => 'erro',
            'message' => 'Credenciais incorretas',
            'errors' => $model->errors
        ];
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
