<?php

namespace backend\modules\api\controllers;

use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class ComentarioController extends ActiveController
{
    public $modelClass = 'common\models\ComentarioUser';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
            'tokenParam' => 'token',
        ];

        return $behaviors;
    }

    //Pegar comentário pelo Id do item
    public function actionPoritem($itemid)
    {
        $model = $this->modelClass;

        return $model::find()
            ->where(['item_id' => $itemid])
            ->with('comentario', 'user')
            ->asArray()
            ->all();
    }

    //Adicionar comentário
    public function actionAddcomentario()
    {
        $body = \Yii::$app->request->bodyParams;

        if (!isset($body['comentario'], $body['user_id'], $body['item_id'])) {
            throw new \yii\web\BadRequestHttpException("Campos obrigatórios: comentario, user_id, item_id.");
        }

        // 1. Criar novo comentário na tabela comentario
        $coment = new \common\models\Comentario();
        $coment->comentario = $body['comentario'];

        if (!$coment->save()) {
            throw new \yii\web\ServerErrorHttpException("Erro ao salvar comentário.");
        }

        // 2. Criar registro na tabela comentario_user
        $comentario_user = new \common\models\ComentarioUser();
        $comentario_user->user_id = $body['user_id'];
        $comentario_user->item_id = $body['item_id'];
        $comentario_user->comentario_id = $coment->id;

        if (!$comentario_user->save()) {
            //throw new \yii\web\ServerErrorHttpException("Erro ao salvar relação do comentário.");
            return $comentario_user->getErrors();
        }

        // 3. Retornar o objeto completo
        return [
            'comentario_id' => $coment->id,
            'comentario' => $coment->comentario,
            'user_id' => $comentario_user->user_id,
            'item_id' => $comentario_user->item_id,
            'status' => 'ok'
        ];
    }
}
