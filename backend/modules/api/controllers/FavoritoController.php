<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use common\models\Favorito;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class FavoritoController extends ActiveController
{
    public $modelClass = 'common\models\Favorito';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
            'tokenParam' => 'token',
        ];

        return $behaviors;
    }

    public function actionPoruser($userid)
    {
        $favoritos = Favorito::find()
            ->where(['user_id' => $userid])
            ->with('colecao')
            ->all();

        if (empty($favoritos)) {
            return [];
        }

        $resultado = [];

        foreach ($favoritos as $fav) {
            if ($fav->colecao) {
                $resultado[] = [
                    'colecao_id' => $fav->colecao->id,
                    'nome'       => $fav->colecao->nome,
                    'descricao'  => $fav->colecao->descricao,
                ];
            }
        }

        return $resultado;
    }

    public function actionAdd()
    {
        $userId = Yii::$app->request->post('user_id');
        $colecaoId = Yii::$app->request->post('colecao_id');

        if (!$userId || !$colecaoId) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Dados inválidos'];
        }

        $existe = \common\models\Favorito::find()
            ->where(['user_id' => $userId, 'colecao_id' => $colecaoId])
            ->one();

        if ($existe) {
            Yii::$app->response->statusCode = 409;
            return ['message' => 'Já está nos favoritos'];
        }

        $fav = new \common\models\Favorito();
        $fav->user_id = $userId;
        $fav->colecao_id = $colecaoId;

        if ($fav->save()) {
            return ['message' => 'Favorito adicionado'];
        }

        Yii::$app->response->statusCode = 500;
        return ['message' => 'Erro ao salvar favorito'];
    }

    public function actionRemover()
    {
        $colecaoId = Yii::$app->request->post('colecao_id');

        if (!$colecaoId) {
            throw new BadRequestHttpException('colecao_id obrigatório');
        }

        // 👇 USER VEM DO TOKEN
        $userId = Yii::$app->user->id;

        $favorito = Favorito::find()
            ->where([
                'user_id' => $userId,
                'colecao_id' => $colecaoId
            ])
            ->one();

        if ($favorito === null) {
            throw new NotFoundHttpException('Favorito não encontrado');
        }

        $favorito->delete();

        return [
            'message' => 'Favorito removido com sucesso'
        ];
    }

}
