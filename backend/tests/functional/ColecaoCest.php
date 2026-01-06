<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\models\User;

class ColecaoCest
{
    public function _before(FunctionalTester $I)
    {
        // cleanup handled by Yii2 module transaction rollback
        
        // Precisamos estar logados para mexer nas coleções (Backend)
        $I->haveRecord(User::class, [
            'username' => 'admin_cols',
            'email' => 'cols@teste.com',
            'password_hash' => \Yii::$app->security->generatePasswordHash('admin123'),
            'status' => User::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        
        $user = User::findByUsername('admin_cols');
        $auth = \Yii::$app->authManager;
        $auth->assign($auth->getPermission('accessBackend'), $user->id);
        $auth->assign($auth->getPermission('manageAllColecoes'), $user->id);
        $auth->assign($auth->getRole('admin'), $user->id);
        
        $I->amLoggedInAs($user->id);
    }

    // TESTE 3: Criar e Listar uma Nova Coleção
    public function criarEListarColecao(FunctionalTester $I)
    {
        // 1. Criar
        $I->amOnPage('?r=colecao/create');
        $I->see('Create Colecao');
        
        $I->fillField('Colecao[nome]', 'Minha Coleção Rara');
        $I->fillField('Colecao[descricao]', 'Coleção de teste automatizado');
        $I->click('Save'); 
        
        $I->see('Minha Coleção Rara'); // View page

        // 2. Listar
        $I->amOnPage('?r=colecao/index');
        $I->see('Minha Coleção Rara');
    }

    // TESTE 4: Validar Campos Obrigatórios (Coleção)
    public function tentarCriarColecaoVazia(FunctionalTester $I)
    {
        $I->amOnPage('?r=colecao/create');
        $I->click('Save'); 
        
        $I->see('Nome cannot be blank'); 
    }
}