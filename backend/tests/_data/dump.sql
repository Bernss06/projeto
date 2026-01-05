SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `user`;

DROP TABLE IF EXISTS `colecao`;

DROP TABLE IF EXISTS `item`;

DROP TABLE IF EXISTS `favorito`;

DROP TABLE IF EXISTS `gosto`;

DROP TABLE IF EXISTS `pfpimage`;

DROP TABLE IF EXISTS `auth_assignment`;

DROP TABLE IF EXISTS `auth_item_child`;

DROP TABLE IF EXISTS `auth_item`;

DROP TABLE IF EXISTS `auth_rule`;

-- USER TABLE (from previous steps)
CREATE TABLE `user` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
    `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `status` smallint(6) NOT NULL DEFAULT '10',
    `created_at` int(11) NOT NULL,
    `updated_at` int(11) NOT NULL,
    `verification_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`),
    UNIQUE KEY `email` (`email`),
    UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

CREATE TABLE `colecao` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `descricao` text COLLATE utf8_unicode_ci,
    `status` smallint(6) DEFAULT '0',
    `user_id` int(11) DEFAULT NULL,
    `created_at` int(11) DEFAULT NULL,
    `updated_at` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx-colecao-user_id` (`user_id`),
    CONSTRAINT `fk-colecao-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

CREATE TABLE `item` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nome` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
    `descricao` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
    `nota` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
    `dtaquisicao` date DEFAULT NULL,
    `nome_foto` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
    `categoria_id` int(11) DEFAULT '0',
    `colecao_id` int(11) NOT NULL,
    `created_at` int(11) DEFAULT NULL,
    `updated_at` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx-item-colecao_id` (`colecao_id`),
    CONSTRAINT `fk-item-colecao_id` FOREIGN KEY (`colecao_id`) REFERENCES `colecao` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

CREATE TABLE `favorito` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `colecao_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx-favorito-unique` (`colecao_id`, `user_id`),
    KEY `idx-favorito-colecao_id` (`colecao_id`),
    KEY `idx-favorito-user_id` (`user_id`),
    CONSTRAINT `fk-favorito-colecao_id` FOREIGN KEY (`colecao_id`) REFERENCES `colecao` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk-favorito-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

CREATE TABLE `gosto` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `item_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx-gosto-item_id` (`item_id`),
    KEY `idx-gosto-user_id` (`user_id`),
    CONSTRAINT `fk-gosto-item_id` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk-gosto-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

CREATE TABLE `pfpimage` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nome` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
    `user_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx-pfpimage-user_id` (`user_id`),
    CONSTRAINT `fk-pfpimage-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

CREATE TABLE `agenda` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `estado` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `item_id` int(11) NOT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx-agenda-user_id` (`user_id`),
    KEY `idx-agenda-item_id` (`item_id`),
    CONSTRAINT `fk-agenda-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk-agenda-item_id` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

-- RBAC TABLES
CREATE TABLE `auth_rule` (
    `name` varchar(64) NOT NULL,
    `data` blob,
    `created_at` int(11) DEFAULT NULL,
    `updated_at` int(11) DEFAULT NULL,
    PRIMARY KEY (`name`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

CREATE TABLE `auth_item` (
    `name` varchar(64) NOT NULL,
    `type` smallint(6) NOT NULL,
    `description` text,
    `rule_name` varchar(64) DEFAULT NULL,
    `data` blob,
    `created_at` int(11) DEFAULT NULL,
    `updated_at` int(11) DEFAULT NULL,
    PRIMARY KEY (`name`),
    KEY `rule_name` (`rule_name`),
    KEY `idx-auth_item-type` (`type`),
    CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

CREATE TABLE `auth_item_child` (
    `parent` varchar(64) NOT NULL,
    `child` varchar(64) NOT NULL,
    PRIMARY KEY (`parent`, `child`),
    KEY `child` (`child`),
    CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

CREATE TABLE `auth_assignment` (
    `item_name` varchar(64) NOT NULL,
    `user_id` varchar(64) NOT NULL,
    `created_at` int(11) DEFAULT NULL,
    PRIMARY KEY (`item_name`, `user_id`),
    CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

-- SEED DATA
-- 1 = Role, 2 = Permission
INSERT INTO
    `auth_item` (
        `name`,
        `type`,
        `description`,
        `created_at`,
        `updated_at`
    )
VALUES (
        'accessBackend',
        2,
        'Access Backend Area',
        UNIX_TIMESTAMP(),
        UNIX_TIMESTAMP()
    ),
    (
        'manageAllColecoes',
        2,
        'Gerir todas as colecoes',
        UNIX_TIMESTAMP(),
        UNIX_TIMESTAMP()
    ),
    (
        'admin',
        1,
        'Administrador',
        UNIX_TIMESTAMP(),
        UNIX_TIMESTAMP()
    );

SET FOREIGN_KEY_CHECKS = 1;