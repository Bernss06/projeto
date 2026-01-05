<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\models\User;
use common\models\Troca;

/**
 * Class TrocaCest
 */
class TrocaCest
{
    public function _before(FunctionalTester $I)
    {
        // 1. Criar User Dono do Item
        $I->haveRecord(User::class, [
            'username' => 'user_dono',
            'email' => 'dono@teste.com',
            'status' => 10,
            'password_hash' => \Yii::$app->security->generatePasswordHash('password'),
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $userDono = User::findByUsername('user_dono');
        
        // 2. Criar Coleção do Dono
        $I->haveRecord(\common\models\Colecao::class, [
            'nome' => 'Coleção do Dono',
            'user_id' => $userDono->id,
            'status' => 1
        ]);
        $colecao = \common\models\Colecao::findOne(['user_id' => $userDono->id]);

        // 3. Criar Item do Dono
        $I->haveRecord(\common\models\Item::class, [
            'nome' => 'Item Raro',
            'colecao_id' => $colecao->id,
        ]);
        $item = \common\models\Item::findOne(['colecao_id' => $colecao->id]);
        $this->itemId = $item->id;

        // 4. Criar e Logar como User Pedinte (admin_troca)
        $I->haveRecord(User::class, [
            'username' => 'admin_troca',
            'email' => 'troca@teste.com',
            'status' => 10,
            'password_hash' => \Yii::$app->security->generatePasswordHash('password'),
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->userPedinte = User::findByUsername('admin_troca');
        
        $I->amLoggedInAs($this->userPedinte->id);
    }

    protected $itemId;
    protected $userPedinte;

    // TESTE ÚNICO: Fluxo de Trocas (API e DB)
    public function validarFluxoTrocas(FunctionalTester $I)
    {
        // 1. Acessar API (Ver se não crasha)
        $I->sendGET('index-test.php', ['r' => 'api/agenda/trocasporuser', 'userid' => $this->userPedinte->id]);
        $I->seeResponseCodeIs(200);

        // 2. Criar Troca via DB (Simulando Frontend)
        $I->haveRecord(Troca::class, [
            'user_id' => $this->userPedinte->id,
            'item_id' => $this->itemId,
            'estado' => Troca::STATUS_PENDENTE,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // 3. Verificar persistência
        // Nota: recordRecord funciona se tivermos ID. seeRecord é o método correcto.
        $I->seeRecord(Troca::class, [
            'user_id' => $this->userPedinte->id,
            'item_id' => $this->itemId,
            'estado' => 0 
        ]);
        
        // 4. Verificar se a API retorna a nova troca
        $I->sendGET('index-test.php', ['r' => 'api/agenda/trocasporuser', 'userid' => $this->userPedinte->id]);
        $I->seeResponseContainsJson(['item_id' => $this->itemId]);
    }
}
