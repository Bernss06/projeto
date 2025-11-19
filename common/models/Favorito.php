<?php

namespace common\models;

use app\common\models\Colecao;
use Yii;

/**
 * This is the model class for table "favorito".
 *
 * @property int $id
 * @property int $coelcao_id
 *
 * @property Colecao $coelcao
 */
class Favorito extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'favorito';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['colecao_id'], 'required'],
            [['colecao_id'], 'integer'],
            [['colecao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Colecao::class, 'targetAttribute' => ['colecao_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'coelcao_id' => 'Coelcao ID',
        ];
    }

    /**
     * Gets query for [[Coelcao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCoelcao()
    {
        return $this->hasOne(Colecao::class, ['id' => 'coelcao_id']);
    }

}
