<?php

namespace common\rbac;

use Yii;
use yii\rbac\Rule;
use common\models\Colecao;

/**
 * Rule para verificar se o utilizador é dono da coleção
 */
class ColecaoOwnerRule extends Rule
{
    public $name = 'isColecaoOwner';

    /**
     * @param string|int $user ID do utilizador
     * @param \yii\rbac\Item $item o papel ou permissão que está a ser verificado
     * @param array $params parâmetros passados para Yii::$app->user->can()
     * @return bool verdadeiro se o utilizador é dono da coleção
     */
    public function execute($user, $item, $params)
    {
        // Se não houver coleção nos parâmetros, não permitir
        if (!isset($params['colecao'])) {
            return false;
        }

        $colecao = $params['colecao'];
        
        // Se for um ID, buscar a coleção
        if (is_numeric($colecao)) {
            $colecao = Colecao::findOne($colecao);
            if ($colecao === null) {
                return false;
            }
        }
        
        // Se não for uma instância de Colecao, não permitir
        if (!($colecao instanceof Colecao)) {
            return false;
        }

        // Verificar se o utilizador é o dono da coleção
        return (int)$colecao->user_id === (int)$user;
    }
}

