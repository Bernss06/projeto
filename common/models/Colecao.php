<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\ForbiddenHttpException;

class Colecao extends ActiveRecord
{
    /**
     * Virtual attribute to control visibilidade da coleção.
     *
     * @var bool|int
     */
    public $is_public = 0;

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

    /**
     * Delete all related items and favoritos before deleting the collection
     * to prevent foreign key constraint violations.
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        // Delete all items in this collection
        Item::deleteAll(['colecao_id' => $this->id]);

        // Delete all favoritos for this collection
        ColecaoFavorito::deleteAll(['colecao_id' => $this->id]);

        return true;
    }

    public function isPublic(): bool
    {
        return (int)$this->status === 1;
    }

    /**
     * Get the user that owns the collection.
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getItens()
    {
        return $this->hasMany(Item::class, ['colecao_id' => 'id'])->orderBy(['dtaquisicao' => SORT_DESC, 'id' => SORT_DESC]);
    }

    public function getFavoritos()
    {
        return $this->hasMany(ColecaoFavorito::class, ['colecao_id' => 'id']);
    }

    /**
     * Verifica se a coleção foi favoritada por um utilizador específico
     * 
     * @param int $userId ID do utilizador
     * @return bool
     */
    public function isFavoritedByUser(int $userId): bool
    {
        return ColecaoFavorito::find()
            ->where(['colecao_id' => $this->id, 'user_id' => $userId])
            ->exists();
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


