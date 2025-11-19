<?php

namespace common\rbac;

use Yii;
use yii\rbac\Rule;
use common\models\Item;
use common\models\Colecao;

/**
 * Rule para verificar se o utilizador é dono do item (através da coleção)
 */
class ItemOwnerRule extends Rule
{
    public $name = 'isItemOwner';

    /**
     * @param string|int $user ID do utilizador
     * @param \yii\rbac\Item $item o papel ou permissão que está a ser verificado
     * @param array $params parâmetros passados para Yii::$app->user->can()
     * @return bool verdadeiro se o utilizador é dono do item (através da coleção)
     */
    public function execute($user, $item, $params)
    {
        // Se não houver item nos parâmetros, não permitir
        if (!isset($params['item'])) {
            return false;
        }

        $itemModel = $params['item'];
        
        // Se for um ID, buscar o item
        if (is_numeric($itemModel)) {
            $itemModel = Item::findOne($itemModel);
            if ($itemModel === null) {
                return false;
            }
        }
        
        // Se não for uma instância de Item, não permitir
        if (!($itemModel instanceof Item)) {
            return false;
        }

        // Buscar a coleção do item
        $colecao = $itemModel->colecao;
        if ($colecao === null) {
            return false;
        }

        // Verificar se o utilizador é o dono da coleção (e portanto do item)
        return (int)$colecao->user_id === (int)$user;
    }
}

