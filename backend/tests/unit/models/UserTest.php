<?php

namespace backend\tests\unit\models;

use common\models\User;
use backend\tests\UnitTester;

class UserTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // TESTE 1: Validação de Username (Verifica regra 'min' => 2)
    public function testValidarUsernameCurto()
    {
        $user = new User();
        $user->username = 'a'; // Demasiado curto
        $user->email = 'teste@exemplo.com';
        $user->setPassword('password123');

        $this->assertFalse($user->validate(['username']), 'O modelo deve rejeitar usernames com menos de 2 caracteres');
    }

    // TESTE 2: Validação de Email (Verifica regra 'email')
    public function testValidarEmailInvalido()
    {
        $user = new User();
        $user->username = 'TesteUser';
        $user->email = 'email_sem_arroba_ponto_com'; // Formato inválido
        $user->setPassword('password123');

        $this->assertFalse($user->validate(['email']), 'O modelo deve rejeitar emails inválidos');
    }

    // TESTE 3: Lógica de Negócio - Password Hashing
    // Verifica se a password crua é transformada numa hash segura
    public function testPasswordHashing()
    {
        $user = new User();
        $passwordCrua = 'minhaSenhaSecreta';
        $user->setPassword($passwordCrua);

        $this->assertNotEmpty($user->password_hash, 'A hash da password não deve estar vazia');
        $this->assertNotEquals($passwordCrua, $user->password_hash, 'A password não deve ser guardada em texto limpo');
        $this->assertTrue($user->validatePassword($passwordCrua), 'A validação da password deve funcionar com a hash gerada');
    }

    // TESTE 4: Lógica de Negócio - Status Padrão
    // Verifica se um novo user fica com STATUS_ACTIVE (10) automaticamente
    public function testStatusPadraoAoCriar()
    {
        $user = new User();
        $user->username = 'UserStatus';
        $user->email = 'status@exemplo.com';
        
        // Não definimos o status explicitamente
        $user->validate();
        
        $this->assertEquals(User::STATUS_ACTIVE, $user->status, 'O status padrão deve ser ACTIVE (10)');
    }

    // TESTE 5: Integração com Base de Dados (Active Record)
    // Grava um user e verifica se existe na tabela 'user'
    public function testSalvarUsuarioNaBaseDeDados()
    {
        $user = new User();
        $user->username = 'NovoUserBD';
        $user->email = 'bancodedados@exemplo.com';
        $user->setPassword('senha123456');
        $user->status = User::STATUS_ACTIVE;

        // Tenta salvar
        $this->assertTrue($user->save(), 'O utilizador deve ser salvo com sucesso');

        // Verifica na tabela 'user' se o registo existe
        $this->tester->seeRecord(User::class, [
            'username' => 'NovoUserBD',
            'email' => 'bancodedados@exemplo.com'
        ]);
    }
}
