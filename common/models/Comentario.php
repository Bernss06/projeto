<?php

namespace common\models;

/**
 * This is the model class for table "comentario".
 *
 * @property int $id
 * @property string $comentario
 *
 * @property ComentarioUser[] $comentarioUsers
 */
class Comentario extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comentario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comentario'], 'required'],
            [['comentario'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comentario' => 'Comentario',
        ];
    }

    /**
     * Gets query for [[ComentarioUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComentarioUsers()
    {
        return $this->hasMany(ComentarioUser::class, ['comentario_id' => 'id']);
    }

}
