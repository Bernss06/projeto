<?php

namespace common\models;

use common\models\Item;
use common\models\User;

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
