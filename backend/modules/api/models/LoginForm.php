<?php

namespace backend\modules\api\models;

use yii\base\Model;
use common\models\User;

class LoginForm extends Model
{
    public $username;
    public $password;

    /**
     * @var User|null
     */
    private $_user = null;

    /**
     * Regras de validação
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'email'],
            ['password', 'string', 'min' => 6],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Valida a senha com hash
     */
    public function validatePassword($attribute, $params)
    {
        if ($this->hasErrors()) {
            return;
        }

        $user = $this->getUser();

        if (!$user || !$user->validatePassword($this->password)) {
            $this->addError($attribute, 'Email ou senha inválidos.');
        }
    }

    /**
     * Retorna o usuário pelo username
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::find()
                ->where(['username' => $this->username])
                ->one();
        }

        return $this->_user;
    }
}
