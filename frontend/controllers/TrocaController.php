<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\Troca;
use common\models\Item;

class TrocaController extends Controller
{
    /**
     * Criar pedido de troca (pendente)
     */
    public function actionCreate($item_id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $item = Item::findOne($item_id);
        if (!$item) {
            throw new NotFoundHttpException('Item não encontrado.');
        }

        $troca = new Troca();
        $troca->user_origem_id = Yii::$app->user->id;
        $troca->user_destino_id = $item->colecao->user_id;
        $troca->item_recebido_id = $item->id;
        $troca->status = 'pendente';
        $troca->data_troca = date('Y-m-d H:i:s');

        $troca->save(false);

        Yii::$app->session->setFlash('success', 'Pedido de troca enviado!');
        return $this->redirect(['site/historicotrocas']);
    }

    /**
     * Ver detalhes da troca
     */
    public function actionView($id)
    {
        $troca = Troca::findOne($id);

        if (!$troca) {
            throw new NotFoundHttpException('Troca não encontrada.');
        }

        // Apenas participantes podem ver
        if (
            $troca->user_origem_id !== Yii::$app->user->id &&
            $troca->user_destino_id !== Yii::$app->user->id
        ) {
            throw new NotFoundHttpException();
        }

        return $this->render('view', [
            'troca' => $troca,
        ]);
    }

    /**
     * Aceitar troca
     */
    public function actionAceitar($id)
    {
        $troca = Troca::findOne($id);

        if (
            !$troca ||
            $troca->user_destino_id !== Yii::$app->user->id ||
            $troca->status !== 'pendente'
        ) {
            throw new NotFoundHttpException();
        }

        $troca->status = 'aceite';
        $troca->save(false);

        return $this->redirect(['site/historicotrocas']);
    }

    /**
     * Recusar troca
     */
    public function actionRecusar($id)
    {
        $troca = Troca::findOne($id);

        if (
            !$troca ||
            $troca->user_destino_id !== Yii::$app->user->id ||
            $troca->status !== 'pendente'
        ) {
            throw new NotFoundHttpException();
        }

        $troca->status = 'recusada';
        $troca->save(false);

        return $this->redirect(['site/historicotrocas']);
    }
}

