<?php

use yii\db\Migration;

class m241203_000002_create_colecao_favorito_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%colecao_favorito}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'colecao_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('uq_colecao_favorito_user_colecao', '{{%colecao_favorito}}', ['user_id', 'colecao_id'], true);
        $this->createIndex('idx_colecao_favorito_colecao', '{{%colecao_favorito}}', 'colecao_id');

        $this->addForeignKey(
            'fk_colecao_favorito_user',
            '{{%colecao_favorito}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_colecao_favorito_colecao',
            '{{%colecao_favorito}}',
            'colecao_id',
            '{{%colecao}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_colecao_favorito_colecao', '{{%colecao_favorito}}');
        $this->dropForeignKey('fk_colecao_favorito_user', '{{%colecao_favorito}}');
        $this->dropTable('{{%colecao_favorito}}');
    }
}


