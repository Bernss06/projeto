-- Adicionar coluna user_id à tabela favorito para suportar favoritos por utilizador
ALTER TABLE `favorito`
ADD COLUMN `user_id` INT(11) NOT NULL AFTER `colecao_id`,
ADD INDEX `idx_favorito_user_id` (`user_id`),
ADD CONSTRAINT `fk_favorito_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Remover registros duplicados existentes (se houver)
-- Como não havia user_id antes, vamos limpar a tabela para começar do zero
TRUNCATE TABLE `favorito`;