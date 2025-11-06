<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * LoginForm model
 *
 * Este modelo é responsável por autenticar o utilizador.
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    /**
     * @return array regras de validação
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required', 'message' => 'Este campo é obrigatório.'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Valida a senha.
     * Este método é chamado pela regra de validação acima.
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Email ou senha incorretos.');
            }
        }
    }

    /**
     * Faz o login do utilizador.
     *
     * @return bool se o login foi bem-sucedido
     */
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();

            if (Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0)) {
                // ✅ Redireciona após login
                Yii::$app->response->redirect(['site/dashboard'])->send();
                return true;
            }
        }
        return false;
    }

    /**
     * Encontra o utilizador pelo email/username.
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
