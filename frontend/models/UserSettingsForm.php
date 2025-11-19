<?php

namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;

class UserSettingsForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_repeat;
    public $theme;

    private User $user;

    public function __construct(User $user, $config = [])
    {
        $this->user = $user;
        $this->username = $user->username;
        $this->email = $user->email;

        if ($user instanceof \yii\db\BaseActiveRecord && $user->hasAttribute('theme')) {
            $this->theme = $user->getAttribute('theme') ?: 'dark';
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['username', 'email'], 'trim'],
            [['username', 'email'], 'string', 'max' => 255],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->user->id], 'message' => 'Este email já está em uso.'],
            ['username', 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->user->id], 'message' => 'Este nome já está em uso.'],
            [['password', 'password_repeat'], 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => true],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Nome',
            'email' => 'Email',
            'password' => 'Nova Password',
            'password_repeat' => 'Confirmar Password',
        ];
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $this->user->username = $this->username;
        $this->user->email = $this->email;

        if ($this->user instanceof \yii\db\BaseActiveRecord && $this->user->hasAttribute('theme')) {
            $this->user->setAttribute('theme', $this->theme ?? $this->user->getAttribute('theme') ?? 'dark');
        }

        if ($this->password) {
            $this->user->setPassword($this->password);
        }

        return $this->user->save(false);
    }
}


