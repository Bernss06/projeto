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

        // Impedir pedir troca do próprio item
        if ($item->colecao->user_id == Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', 'Não pode pedir troca do seu próprio item.');
            return $this->redirect(['colecao/view', 'id' => $item->colecao_id]);
        }

        $troca = new Troca();
        $troca->user_id = Yii::$app->user->id; // Quem pede
        $troca->item_id = $item->id; // Item pedido
        $troca->estado = Troca::STATUS_PENDENTE;
        $troca->created_at = date('Y-m-d H:i:s');
        $troca->updated_at = date('Y-m-d H:i:s');

        if ($troca->save()) {
            Yii::$app->session->setFlash('success', 'Pedido de troca enviado!');
        } else {
            Yii::$app->session->setFlash('error', 'Erro ao enviar pedido de troca.');
        }

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

        // Determinar quem pode ver: Requester ou Owner
        $isRequester = $troca->user_id === Yii::$app->user->id;
        $isOwner = $troca->item->colecao->user_id === Yii::$app->user->id;

        if (!$isRequester && !$isOwner) {
            throw new NotFoundHttpException('Não tem permissão para ver esta troca.');
        }

        return $this->render('view', [
            'troca' => $troca,
            'isOwner' => $isOwner,
        ]);
    }

    /**
     * Aceitar troca
     */
    public function actionAceitar($id)
    {
        $troca = Troca::findOne($id);
        if (!$troca) { throw new NotFoundHttpException(); }

        $isOwner = $troca->item->colecao->user_id === Yii::$app->user->id;

        if (!$isOwner || $troca->estado != Troca::STATUS_PENDENTE) {
            throw new NotFoundHttpException('Não permitido.');
        }

        $troca->estado = Troca::STATUS_ACEITE;
        $troca->updated_at = date('Y-m-d H:i:s');
        $troca->save(false);

        return $this->redirect(['site/historicotrocas']);
    }

    /**
     * Recusar troca
     */
    public function actionRecusar($id)
    {
        $troca = Troca::findOne($id);
        if (!$troca) { throw new NotFoundHttpException(); }

        $isOwner = $troca->item->colecao->user_id === Yii::$app->user->id;

        if (!$isOwner || $troca->estado != Troca::STATUS_PENDENTE) {
            throw new NotFoundHttpException('Não permitido.');
        }

        $troca->estado = Troca::STATUS_RECUSADA;
        $troca->updated_at = date('Y-m-d H:i:s');
        $troca->save(false);

        return $this->redirect(['site/historicotrocas']);
    }
}

