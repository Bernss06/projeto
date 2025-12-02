<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Colecao;
use common\models\Item;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
        

        // ===============================//
        // CRIAR PERMISSÕES DO FRONTEND   //
        // ===============================//
        
        // Permissões de Coleções
        $createColecao = $auth->createPermission('createColecao');
        $createColecao->description = 'Criar uma nova coleção';
        $auth->add($createColecao);
        
        $updateOwnColecao = $auth->createPermission('updateOwnColecao');
        $updateOwnColecao->description = 'Atualizar própria coleção';

        $auth->add($updateOwnColecao);
        
        $deleteOwnColecao = $auth->createPermission('deleteOwnColecao');
        $deleteOwnColecao->description = 'Eliminar própria coleção';

        $auth->add($deleteOwnColecao);
        
        $viewOwnColecao = $auth->createPermission('viewOwnColecao');
        $viewOwnColecao->description = 'Ver própria coleção (privada)';

        $auth->add($viewOwnColecao);
        
        $viewPublicColecao = $auth->createPermission('viewPublicColecao');
        $viewPublicColecao->description = 'Ver coleções públicas';
        $auth->add($viewPublicColecao);
        
        $manageFavorites = $auth->createPermission('manageFavorites');
        $manageFavorites->description = 'Gerir favoritos';
        $auth->add($manageFavorites);
        
        // Permissões de Itens
        $createItem = $auth->createPermission('createItem');
        $createItem->description = 'Criar um novo item';
        $auth->add($createItem);
        
        $updateOwnItem = $auth->createPermission('updateOwnItem');
        $updateOwnItem->description = 'Atualizar próprio item';

        $auth->add($updateOwnItem);
        
        $deleteOwnItem = $auth->createPermission('deleteOwnItem');
        $deleteOwnItem->description = 'Eliminar próprio item';

        $auth->add($deleteOwnItem);
        
        $viewOwnItem = $auth->createPermission('viewOwnItem');
        $viewOwnItem->description = 'Ver próprio item';

        $auth->add($viewOwnItem);
        
        // ===============================//
        // CRIAR PERMISSÕES DO BACKEND    //
        // ===============================//
        
        $accessBackend = $auth->createPermission('accessBackend');
        $accessBackend->description = 'Aceder ao painel administrativo';
        $auth->add($accessBackend);
        
        $manageUsers = $auth->createPermission('manageUsers');
        $manageUsers->description = 'Gerir utilizadores';
        $auth->add($manageUsers);
        
        $manageAllColecoes = $auth->createPermission('manageAllColecoes');
        $manageAllColecoes->description = 'Gerir todas as coleções';
        $auth->add($manageAllColecoes);
        
        $manageAllItems = $auth->createPermission('manageAllItems');
        $manageAllItems->description = 'Gerir todos os itens';
        $auth->add($manageAllItems);
        
        // =======================//
        // CRIAR PAPÉIS (ROLES)   //
        // =======================//   
        
        // Papel: Colecionador (utilizador normal)
        $colecionador = $auth->createRole('colecionador');
        $auth->add($colecionador);
        
        // Atribuir permissões ao papel colecionador
        $auth->addChild($colecionador, $createColecao);
        $auth->addChild($colecionador, $updateOwnColecao);
        $auth->addChild($colecionador, $deleteOwnColecao);
        $auth->addChild($colecionador, $viewOwnColecao);
        $auth->addChild($colecionador, $viewPublicColecao);
        $auth->addChild($colecionador, $manageFavorites);
        $auth->addChild($colecionador, $createItem);
        $auth->addChild($colecionador, $updateOwnItem);
        $auth->addChild($colecionador, $deleteOwnItem);
        $auth->addChild($colecionador, $viewOwnItem);
        
        // Papel: Admin (administrador)
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        
        // Admin herda todas as permissões do colecionador
        $auth->addChild($admin, $colecionador);
        
        // Admin tem permissões adicionais
        $auth->addChild($admin, $accessBackend);
        $auth->addChild($admin, $manageUsers);
        $auth->addChild($admin, $manageAllColecoes);
        $auth->addChild($admin, $manageAllItems);
        
        // =================================//
        // ATRIBUIR PAPÉIS A UTILIZADORES   //
        // =================================//
        
        // Atribuir papel admin ao utilizador com ID 1
        $auth->assign($admin, 1);
        
        $this->stdout("RBAC inicializado com sucesso!\n", \yii\helpers\Console::FG_GREEN);
        $this->stdout("Papéis criados: colecionador, admin\n");
        $this->stdout("Permissões criadas e atribuídas aos papéis.\n");
    }
}
