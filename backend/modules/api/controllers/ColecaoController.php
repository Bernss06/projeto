<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class ColecaoController extends ActiveController
{
    public $modelClass = 'common\models\Colecao';

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
