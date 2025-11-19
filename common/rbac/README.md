# Sistema RBAC - Regras e Permissões

Este documento descreve o sistema de controle de acesso baseado em papéis (RBAC) implementado no projeto.

## Papéis (Roles)

### `colecionador`
Utilizador normal que pode:
- Criar e gerir as suas próprias coleções
- Criar e gerir itens nas suas coleções
- Ver coleções públicas
- Gerir favoritos

### `admin`
Administrador que herda todas as permissões do `colecionador` e adicionalmente pode:
- Aceder ao painel administrativo (backend)
- Gerir utilizadores
- Gerir todas as coleções (não apenas as suas)
- Gerir todos os itens (não apenas os seus)

## Permissões Criadas

### Frontend - Coleções
- `createColecao` - Criar uma nova coleção
- `updateOwnColecao` - Atualizar própria coleção (com rule)
- `deleteOwnColecao` - Eliminar própria coleção (com rule)
- `viewOwnColecao` - Ver própria coleção privada (com rule)
- `viewPublicColecao` - Ver coleções públicas
- `manageFavorites` - Gerir favoritos

### Frontend - Itens
- `createItem` - Criar um novo item
- `updateOwnItem` - Atualizar próprio item (com rule)
- `deleteOwnItem` - Eliminar próprio item (com rule)
- `viewOwnItem` - Ver próprio item (com rule)

### Backend
- `accessBackend` - Aceder ao painel administrativo
- `manageUsers` - Gerir utilizadores
- `manageAllColecoes` - Gerir todas as coleções
- `manageAllItems` - Gerir todos os itens

## Rules (Regras Dinâmicas)

### `ColecaoOwnerRule`
Verifica se o utilizador é dono da coleção. Usada nas permissões:
- `updateOwnColecao`
- `deleteOwnColecao`
- `viewOwnColecao`

### `ItemOwnerRule`
Verifica se o utilizador é dono do item (através da coleção). Usada nas permissões:
- `updateOwnItem`
- `deleteOwnItem`
- `viewOwnItem`

## Como Usar nos Controladores

### Exemplo 1: Verificar permissão simples

```php
if (Yii::$app->user->can('createColecao')) {
    // Permitir criar coleção
}
```

### Exemplo 2: Verificar permissão com rule (coleção)

```php
$colecao = Colecao::findOne($id);
if (Yii::$app->user->can('updateOwnColecao', ['colecao' => $colecao])) {
    // Permitir atualizar (apenas se for dono)
}
```

### Exemplo 3: Verificar permissão com rule (item)

```php
$item = Item::findOne($id);
if (Yii::$app->user->can('updateOwnItem', ['item' => $item])) {
    // Permitir atualizar (apenas se for dono da coleção)
}
```

### Exemplo 4: Usar em AccessControl

```php
public function behaviors()
{
    return [
        'access' => [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function ($rule, $action) {
                        return Yii::$app->user->can('createColecao');
                    },
                ],
                [
                    'actions' => ['update'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function ($rule, $action) {
                        $id = Yii::$app->request->get('id');
                        $colecao = Colecao::findOne($id);
                        return Yii::$app->user->can('updateOwnColecao', ['colecao' => $colecao]);
                    },
                ],
            ],
        ],
    ];
}
```

## Inicialização

Para inicializar o sistema RBAC, execute:

```bash
php yii rbac/init
```

Este comando irá:
1. Remover todas as regras, permissões e papéis existentes
2. Criar as rules dinâmicas
3. Criar todas as permissões
4. Criar os papéis e atribuir permissões
5. Atribuir o papel `admin` ao utilizador com ID 1

## Notas Importantes

- As rules verificam dinamicamente a propriedade dos recursos
- O papel `admin` herda todas as permissões do `colecionador`
- As permissões com rules requerem que o objeto (coleção/item) seja passado como parâmetro
- Para atribuir o papel `colecionador` a novos utilizadores, use: `Yii::$app->authManager->assign($colecionador, $userId)`

