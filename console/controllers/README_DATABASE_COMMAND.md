# Comando de Reset da Base de Dados

Este comando permite limpar completamente a base de dados do projeto, mantendo apenas:

- O utilizador **admin**
- As 2 **pfp (imagens de perfil) padrão**: `pfppadrao.png` (id 6) e `admin.png` (id 8)

## Como Usar

### Reset Completo da Base de Dados

```bash
php yii database/reset
```

Este comando irá:

1. ✓ Apagar todos os **favoritos**
2. ✓ Apagar todos os **itens**
3. ✓ Apagar todas as **coleções**
4. ✓ Apagar todos os **utilizadores** (exceto admin)
5. ✓ Apagar todas as **imagens de perfil** (exceto as 2 padrão)

### Ver Estatísticas da Base de Dados

```bash
php yii database/stats
```

Mostra o número atual de:

- 👥 Utilizadores
- 📦 Coleções
- 📝 Itens
- ⭐ Favoritos
- 🖼️ Imagens de Perfil

## Confirmações de Segurança

O comando `database/reset` pede **duas confirmações** antes de executar:

1. Confirmação inicial
2. Confirmação final (para evitar execução acidental)

## ⚠️ AVISO

**Esta operação é IRREVERSÍVEL!** Todos os dados serão permanentemente apagados, exceto:

- Utilizador admin
- 2 PFP padrão: `pfppadrao.png` (id 6) e `admin.png` (id 8)

Use com cuidado!
