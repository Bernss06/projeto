<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pfpimage".
 *
 * @property int $id
 * @property string $nome
 * @property int $user_id
 *
 * @property User $user
 */
class Pfpimage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pfpimage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'user_id'], 'required'],
            [['user_id'], 'integer'],
            [['nome'], 'string', 'max' => 250],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        parent::afterDelete();

        if ($this->nome && $this->nome !== 'pfppadrao.png') {
            $path = Yii::getAlias('@frontend/web/uploads/pfp/') . $this->nome;
            if (file_exists($path)) {
                @unlink($path);
            }
        }
    }
}
