<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $nome
 */
class Categoria extends ActiveRecord
{
    public static function tableName()
    {
        return 'categoria';
    }

    public function rules()
    {
        return [
            [['nome'], 'required'],
            [['nome'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
        ];
    }
}


