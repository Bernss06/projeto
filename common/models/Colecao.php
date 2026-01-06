<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\ForbiddenHttpException;
use app\mosquitto\phpMQTT;

class Colecao extends ActiveRecord
{
    /**
     * Virtual attribute to control visibilidade da coleção.
     *
     * @var bool|int
     */
    public $is_public = 0;

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $data = new \stdClass();
        $data->id = $this->id;
        $data->nome = $this->nome;
        $data->descricao = $this->descricao;
        $data->user_id = $this->user_id;
        $data->status = $this->user_id;

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


    public static function tableName()
    {
        return 'colecao';
    }

    public function init()
    {
        parent::init();
        $this->is_public = (int)$this->status === 1;
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['nome'], 'required'],
            [['descricao'], 'string'],
            [['is_public'], 'boolean'],
            [['is_public'], 'default', 'value' => 0],
            [['status', 'user_id'], 'integer'],
            [['status'], 'default', 'value' => 0],
            [['status'], 'in', 'range' => [0, 1]],
            [['nome'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'descricao' => 'Descrição',
            'is_public' => 'Pública',
            'status' => 'Estado',
            'user_id' => 'Utilizador',
            'created_at' => 'Criada em',
            'updated_at' => 'Atualizada em',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->is_public = (int)$this->status === 1;
    }

    public function beforeValidate()
    {
        if (!parent::beforeValidate()) {
            return false;
        }
        $this->status = (int)(bool)$this->is_public;
        return true;
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->status = (int)(bool)$this->is_public;
        if ($insert) {
            if (Yii::$app->user && !Yii::$app->user->isGuest) {
                $this->user_id = Yii::$app->user->id;
            }
        }
        return true;
    }

    public function isPublic(): bool
    {
        return (int)$this->status === 1;
    }

    public function getItens()
    {
        return $this->hasMany(Item::class, ['colecao_id' => 'id'])->orderBy(['dtaquisicao' => SORT_DESC, 'id' => SORT_DESC]);
    }

    public function getFavoritos()
    {
        return $this->hasMany(ColecaoFavorito::class, ['colecao_id' => 'id']);
    }

    public function isFavoritedByUser(int $userId): bool
    {
        // A tabela favorito não tem user_id, então verificamos apenas se existe na tabela
        // Nota: Isto significa que todos os utilizadores veem os mesmos favoritos
        return $this->getFavoritos()->exists();
    }

    public function getFavoritosCount(): int
    {
        if ($this->isRelationPopulated('favoritos')) {
            return count($this->favoritos);
        }
        return (int)$this->getFavoritos()->count();
    }

    public function canEdit(): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        return (int)$this->user_id === (int)Yii::$app->user->id;
    }

    public function ensureCanView(): void
    {
        if ($this->isPublic()) {
            return;
        }
        if ($this->canEdit()) {
            return;
        }
        throw new ForbiddenHttpException('Você não tem permissão para ver esta coleção.');
    }
}


