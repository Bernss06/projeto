<?php

namespace backend\modules\api\controllers;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class ColecaoController extends ActiveController
{
    public $modelClass = 'common\models\Colecao';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
            'tokenParam' => 'token',
        ];

        return $behaviors;
    }

    // Pegar as Coleções de um utilizador pelo seu Id
    public function actionColecaoporuser($userid){
        $model = new $this->modelClass;
        $recs = $model->find()->where(['user_id' => $userid])->asArray()->all();

        if (!empty($recs)) {
            return $recs; // achou coleções
        }

        throw new \yii\web\NotFoundHttpException("O utilizador $userid não possui coleções.");
    }

    // Contagem de Coleções no Total
    public function actionCount(){
        $colecoesmodel = new $this->modelClass;
        $recs = $colecoesmodel::find()->all();
        return ['count'=>count($recs)];
    }

    // Contagem de Coleções por Utilizador
    public function actionCountporuser($userid){
        $model = $this->modelClass;

        $count = $model::find()
            ->where(['user_id' => $userid])
            ->count();

        return ['count' => $count];
    }

    // Contagem de Itens em cada Coleção
    public function actionCountitenscolecao($colecaoid){
        $model = \common\models\Item::class;

        $count = $model::find()
            ->where(['colecao_id' => $colecaoid])
            ->count();

        return ['count' => $count];
    }

    // Itens presentes em cada Coleção
    public function actionItensporcolecao($colecaoid){
        $model = \common\models\Item::class;

        $itens = $model::find()
            ->where(['colecao_id' => $colecaoid])
            ->asArray()
            ->all();

        if (!empty($itens)) {
            return $itens;
        }

        throw new \yii\web\NotFoundHttpException(
            "A coleção $colecaoid não possui itens registrados."
        );
    }


    /*public function actionDebug()
    {
        try {
            $model = new \common\models\Colecao;
            return [
                'class' => get_class($model),
                'table' => $model::tableName(),
                'count' => $model::find()->count(),
                'sql' => $model::find()->createCommand()->rawSql,
            ];
        } catch (\Throwable $e) {
            return [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ];
        }
    }*/
}
