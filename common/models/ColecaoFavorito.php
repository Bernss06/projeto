<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Modelo para a tabela favorito
 * 
 * @property int $id
 * @property int $colecao_id
 * @property int $user_id
 *
 * @property Colecao $colecao
 * @property User $user
 */
class ColecaoFavorito extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%favorito}}';
    }

    public function rules()
    {
        return [
            [['colecao_id', 'user_id'], 'required'],
            [['colecao_id', 'user_id'], 'integer'],
            [['colecao_id'], 'exist', 'targetClass' => Colecao::class, 'targetAttribute' => ['colecao_id' => 'id']],
            [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            // Garantir que um utilizador só pode favoritar uma coleção uma vez
            [['colecao_id'], 'unique', 'targetAttribute' => ['colecao_id', 'user_id'], 'message' => 'Você já favoritou esta coleção.'],
        ];
    }

    public function beforeValidate()
    {
        if (!parent::beforeValidate()) {
            return false;
        }
        
        // Definir automaticamente o user_id se não estiver definido
        // Isto precisa acontecer ANTES da validação, pois user_id é obrigatório
        if ($this->isNewRecord && !$this->user_id && !Yii::$app->user->isGuest) {
            $this->user_id = Yii::$app->user->id;
        }
        
        return true;
    }

    public function getColecao()
    {
        return $this->hasOne(Colecao::class, ['id' => 'colecao_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}


