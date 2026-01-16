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
            'colecao_id' => 'Coelcao ID',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $data = new \stdClass();
        $data->id = $this->id;
        $data->colecao_id = $this->colecao_id;

        $json = json_encode($data);

        if ($insert)
            $this->publishMosquitto("INSERT", $json);
        else
            $this->publishMosquitto("UPDATE", $json);
    }

    public function afterDelete()
    {
        parent::afterDelete();

        $data = new \stdClass();
        $data->id = $this->id;

        $json = json_encode($data);

        $this->publishMosquitto("DELETE", $json);
    }

    public function publishMosquitto($topic, $msg)
    {
        $server = "127.0.0.1";
        $port = 1883;
        $client_id = "php-publisher";

        $mqtt = new \app\mosquitto\phpMQTT($server, $port, $client_id);

        if ($mqtt->connect()) {
            $mqtt->publish($topic, $msg, 0);
            $mqtt->close();
        }
    }


    /**
     * Gets query for [[Coelcao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCoelcao()
    {
        return $this->hasOne(Colecao::class, ['id' => 'colecao_id']);
    }

}
