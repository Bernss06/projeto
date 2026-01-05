<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\models\User;

class LoginCest
{
    // Executado antes de cada teste
    public function _before(FunctionalTester $I)
    {
        // Cria um utilizador temporário na BD para testar o login
        // (O módulo Db limpará isto no final do teste)
        $I->haveInDatabase('user', [
            'username' => 'admin_teste',
            'email' => 'admin@teste.com',
            'password_hash' => \Yii::$app->security->generatePasswordHash('admin123'),
            'auth_key' => \Yii::$app->security->generateRandomString(),
            'status' => User::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    // TESTE 1: Login com Sucesso (Obrigatório)
    public function loginComSucesso(FunctionalTester $I)
    {
        $I->amOnPage('/site/login'); // Ajusta a rota se necessário
        $I->see('Sign in to start your session'); // Texto do teu HTML
        
        // Preenche o formulário usando os labels ou placeholders
        $I->fillField('Username', 'admin_teste'); 
        $I->fillField('Password', 'admin123');
        
        $I->click('Sign In'); // Texto do botão
        
        // Verifica se entrou (espera ver o Logout ou Dashboard)
        $I->see('Logout'); // Ou outro texto que só aparece quando logado
        $I->dontSee('Sign in to start your session');
    }

    // TESTE 2: Login com Password Errada
    public function loginComPasswordErrada(FunctionalTester $I)
    {
        $I->amOnPage('/site/login');
        
        $I->fillField('Username', 'admin_teste');
        $I->fillField('Password', 'senhaErrada');
        $I->click('Sign In');
        
        // Deve continuar na página de login e mostrar erro
        $I->see('Incorrect username or password'); // Mensagem padrão do Yii2
    }
}