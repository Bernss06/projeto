# Como Executar a Migração SQL

## Opção 1: Via phpMyAdmin (Recomendado)

1. Abra o phpMyAdmin no seu navegador (normalmente em `http://localhost/phpmyadmin`)
2. Selecione a base de dados `projeto` no painel esquerdo
3. Clique na aba **SQL** no topo
4. Cole o seguinte código SQL:

```sql
-- Adicionar coluna user_id à tabela favorito
ALTER TABLE `favorito`
ADD COLUMN `user_id` INT(11) NOT NULL AFTER `colecao_id`,
ADD INDEX `idx_favorito_user_id` (`user_id`),
ADD CONSTRAINT `fk_favorito_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `user`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE;

-- Limpar favoritos existentes (necessário pois não há user_id)
TRUNCATE TABLE `favorito`;
```

5. Clique em **Executar** (ou **Go**)
6. Verifique se apareceu a mensagem de sucesso

## Opção 2: Via Linha de Comando

Execute no PowerShell (ajuste a senha se necessário):

```powershell
Get-Content "c:\wamp64\www\projeto\console\migrations\add_user_id_to_favorito.sql" | c:\wamp64\bin\mysql\mysql9.1.0\bin\mysql.exe -u root -p projeto
```

Quando solicitar, insira a senha do MySQL.

## Verificar se funcionou

Após executar a migration, execute este SQL no phpMyAdmin para verificar:

```sql
DESCRIBE favorito;
```

Deve aparecer a coluna `user_id` na tabela.

## ⚠️ AVISO IMPORTANTE

Esta migration irá **limpar todos os favoritos existentes** porque não há forma de associar os favoritos atuais a utilizadores específicos. Os utilizadores terão que voltar a favoritar as suas coleções.
