<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Troca extends ActiveRecord
{
    /**
     * Nome da tabela no banco de dados
     */
    public static function tableName()
    {
        return 'troca';
    }

    /**
     * Relação com o utilizador que iniciou a troca
     */
    public function getUtilizadorOrigem()
    {
        return $this->hasOne(User::class, ['id' => 'user_origem_id']);
    }

    /**
     * Relação com o utilizador que recebeu o pedido de troca
     */
    public function getUtilizadorDestino()
    {
        return $this->hasOne(User::class, ['id' => 'user_destino_id']);
    }

    /**
     * Relação com o item enviado pelo utilizador de origem
     */
    public function getItemEnviado()
    {
        return $this->hasOne(Item::class, ['id' => 'item_enviado_id']);
    }

    /**
     * Relação com o item que será recebido pelo utilizador de origem
     */
    public function getItemRecebido()
    {
        return $this->hasOne(Item::class, ['id' => 'item_recebido_id']);
    }

    /**
     * Retorna o utilizador parceiro (o outro user na troca)
     * @return User|null
     */
    public function getUtilizadorParceiro()
    {
        if ($this->user_origem_id == Yii::$app->user->id) {
            return $this->utilizadorDestino;
        }
        return $this->utilizadorOrigem;
    }

    /**
     * Retorna o username do parceiro, ou null se não existir
     * @return string|null
     */
    public function getParceiroUsername()
    {
        return $this->utilizadorParceiro ? $this->utilizadorParceiro->username : null;
    }

    /**
     * Retorna o username do utilizador que recusou a troca
     * @return string|null
     */
    public function getRecusouUsername()
    {
        if ($this->status === 'recusada') {
            // Quem recusou é sempre o destinatário
            return $this->utilizadorDestino ? $this->utilizadorDestino->username : null;
        }
        return null;
    }
}
