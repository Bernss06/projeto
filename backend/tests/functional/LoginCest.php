<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\models\User;

class LoginCest
{
    public function _before(FunctionalTester $I)
    {
        // cleanup handled by Yii2 module transaction rollback
        
        // Cria um utilizador temporário na BD para testar o login
        $I->haveRecord(User::class, [
            'username' => 'admin_teste',
            'email' => 'teste@teste.com',
            'status' => 10,
            'password_hash' => \Yii::$app->security->generatePasswordHash('admin123'),
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        
        $user = User::findByUsername('admin_teste');
        $auth = \Yii::$app->authManager;
        $auth->assign($auth->getPermission('accessBackend'), $user->id);
        $auth->assign($auth->getRole('admin'), $user->id);
    }

    // TESTE 1: Login com Sucesso (Obrigatório)
    public function loginComSucesso(FunctionalTester $I)
    {
        $I->amOnPage('/site/login'); // Ajusta a rota se necessário
        $I->see('Sign in to start your session'); // Texto do teu HTML
        
        // Preenche o formulário usando os labels ou placeholders
        $I->fillField('LoginForm[username]', 'admin_teste'); 
        $I->fillField('LoginForm[password]', 'admin123');
        
        $I->click('Sign In'); // Texto do botão
        
        // Verifica se entrou (espera ver o username no dashboard)
        $I->see('admin_teste'); 
        $I->dontSee('Sign in to start your session');
    }

    // TESTE 2: Login com Password Errada
    public function loginComPasswordErrada(FunctionalTester $I)
    {
        $I->amOnPage('/site/login');
        
        $I->fillField('LoginForm[username]', 'admin_teste');
        $I->fillField('LoginForm[password]', 'senhaErrada');
        $I->click('Sign In');
        
        // Deve continuar na página de login e mostrar erro
        $I->see('Incorrect username or password'); // Mensagem padrão do Yii2
    }
}