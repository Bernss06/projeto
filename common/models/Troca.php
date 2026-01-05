<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Troca extends ActiveRecord
{
    const STATUS_PENDENTE = 0;
    const STATUS_ACEITE = 1;
    const STATUS_RECUSADA = 2;

    /**
     * Nome da tabela no banco de dados
     */
    public static function tableName()
    {
        return 'agenda';
    }

    /**
     * Regras de validação (importante para o save funcionar)
     */
    public function rules()
    {
        return [
            [['estado', 'user_id', 'item_id'], 'required'],
            [['estado', 'user_id', 'item_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * Utilizador que fez o pedido (Requester)
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Item que está a ser pedido
     */
    public function getItem()
    {
        return $this->hasOne(Item::class, ['id' => 'item_id']);
    }

    /**
     * Dono do item (Quem recebe o pedido)
     */
    public function getProprietarioItem()
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])
            ->via('item', function ($query) {
                $query->joinWith('colecao');
            });
        // Simplificação: Acesso direto via item->colecao->user
    }

    public function getStatusLabel()
    {
        switch ($this->estado) {
            case self::STATUS_ACEITE: return 'aceite';
            case self::STATUS_RECUSADA: return 'recusada';
            default: return 'pendente';
        }
    }
}
