<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\models\User;

class ColecaoCest
{
    public function _before(FunctionalTester $I)
    {
        // Precisamos estar logados para mexer nas coleções (Backend)
        $id = $I->haveInDatabase('user', [
            'username' => 'admin_cols',
            'email' => 'cols@teste.com',
            'password_hash' => \Yii::$app->security->generatePasswordHash('admin123'),
            'status' => User::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        
        $I->amLoggedInAs($id);
    }

    // TESTE 3: Criar uma Nova Coleção
    public function criarColecao(FunctionalTester $I)
    {
        $I->amOnPage('/colecao/create');
        $I->see('Create Colecao'); // Título padrão do Gii
        
        $I->fillField('Nome', 'Minha Coleção Rara');
        $I->fillField('Descrição', 'Coleção de teste automatizado');
        // Se houver checkbox de pública:
        // $I->checkOption('#colecao-is_public'); 
        
        $I->click('Save'); // Botão padrão
        
        $I->see('Minha Coleção Rara'); // Deve aparecer na página de view
    }

    // TESTE 4: Validar Campos Obrigatórios (Coleção)
    public function tentarCriarColecaoVazia(FunctionalTester $I)
    {
        $I->amOnPage('/colecao/create');
        $I->click('Save'); // Tenta salvar vazio
        
        $I->see('Nome cannot be blank'); // Mensagem de erro padrão
    }

    // TESTE 5: Listar Coleções
    public function verListaDeColecoes(FunctionalTester $I)
    {
        // Cria uma coleção via BD para garantir que há algo para ver
        $I->haveInDatabase('colecao', [
            'nome' => 'Coleção Existente',
            'user_id' => $I->grabFromDatabase('user', 'id', ['username' => 'admin_cols']),
            'status' => 1
        ]);

        $I->amOnPage('/colecao/index');
        $I->see('Coleção Existente');
    }
}