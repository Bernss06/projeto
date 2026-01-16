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

        // --- INÍCIO DA CORREÇÃO ---
        // Se este utilizador não tiver auth_key (porque foi criado manualmente),
        // vamos gerar uma agora mesmo e salvar na BD!
        if (empty($user->auth_key)) {
            $user->generateAuthKey();
            $user->save(false); // Salva ignorando outras validações
        }
        // --- FIM DA CORREÇÃO ---

        return [
            'status' => 'sucesso',
            'user_id' => $user->id,
            'username' => $user->username,
            // Agora temos a certeza absoluta que isto não vai vazio
            'auth_key' => $user->auth_key, 
        ];
    } else {
        Yii::$app->response->statusCode = 401;
        return ['message' => 'Login falhou'];
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
