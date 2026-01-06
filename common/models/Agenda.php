<?php

namespace common\models;

use common\models\Item;
use common\models\User;
use app\mosquitto\phpMQTT;

/**
 * This is the model class for table "agenda".
 *
 * @property int $id
 * @property int $estado
 * @property int $user_id
 * @property int $item_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Item $item
 * @property User $user
 */
class Agenda extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'agenda';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['estado', 'user_id', 'item_id', 'updated_at'], 'required'],
            [['estado', 'user_id', 'item_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::class, 'targetAttribute' => ['item_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'estado' => 'Estado',
            'user_id' => 'User ID',
            'item_id' => 'Item ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $data = new \stdClass();
        $data->id = $this->id;
        $data->estado = $this->estado;
        $data->user_id = $this->user_id;
        $data->item_id = $this->item_id;

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
     * Gets query for [[Item]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::class, ['id' => 'item_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
