<?php

use yii\db\Migration;

class m251209_140653_create_user_id_in_favorito extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m251209_140653_create_user_id_in_favorito cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251209_140653_create_user_id_in_favorito cannot be reverted.\n";

        return false;
    }
    */
}
