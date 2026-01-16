<?php

namespace backend\modules\api\models;

use yii\base\Model;
use common\models\User;
use Yii;

class RegisterForm extends Model
{
    public $username;
    public $email;
    public $password;

    /**
     * Regras de validação
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Cria o usuário
     */
    public function register()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        $user->auth_key = Yii::$app->security->generateRandomString();
        $user->created_at = time();

        return $user->save() ? $user : null;
    }
}
