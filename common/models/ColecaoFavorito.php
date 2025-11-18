<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * Modelo para a tabela favorito existente
 * Nota: A tabela favorito não tem user_id, então não podemos ter favoritos por utilizador
 * 
 * @property int $id
 * @property int $colecao_id (typo na BD: deveria ser colecao_id)
 *
 * @property Colecao $colecao
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
            [['colecao_id'], 'required'],
            [['colecao_id'], 'integer'],
            [['colecao_id'], 'exist', 'targetClass' => Colecao::class, 'targetAttribute' => ['colecao_id' => 'id']],
        ];
    }

    public function getColecao()
    {
        return $this->hasOne(Colecao::class, ['id' => 'colecao_id']);
    }
}


