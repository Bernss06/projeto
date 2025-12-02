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

    public $profileImage;

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
            [['profileImage'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 10],
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

        // Handle Profile Image Upload
        if ($this->profileImage) {
            $path = Yii::getAlias('@frontend/web/uploads/pfp/');
            
            if (!file_exists($path)) {
                if (!mkdir($path, 0777, true)) {
                    $this->addError('profileImage', 'Erro ao criar pasta de upload.');
                    return false;
                }
            }

            $filename = uniqid() . '.' . $this->profileImage->extension;
            $fullPath = $path . $filename;

            if ($this->profileImage->saveAs($fullPath)) {
                // Update or create Pfpimage record
                $pfp = $this->user->pfpimage;
                if (!$pfp) {
                    $pfp = new \common\models\Pfpimage();
                    $pfp->user_id = $this->user->id;
                }
                
                // Old image deletion logic removed to preserve history
                // if ($pfp->nome && $pfp->nome !== 'pfppadrao.png' && file_exists($path . $pfp->nome)) {
                //    unlink($path . $pfp->nome);
                // }

                $pfp->nome = $filename;
                
                $pfp->nome = $filename;
                
                if (!$pfp->save()) {
                    $this->addError('profileImage', 'Erro ao guardar a imagem na base de dados.');
                    return false;
                }
            } else {
                $this->addError('profileImage', 'Erro ao guardar o ficheiro na pasta de uploads. Verifique as permissões.');
                return false;
            }
        }

        return $this->user->save(false);
    }
}


