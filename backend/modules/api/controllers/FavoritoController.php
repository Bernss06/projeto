<?php

namespace backend\modules\api\controllers;

use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use common\models\Favorito;

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
            throw new NotFoundHttpException(
                'Este utilizador não tem favoritos.'
            );
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
}
