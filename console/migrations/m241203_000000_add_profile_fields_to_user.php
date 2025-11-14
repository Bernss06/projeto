<?php

use yii\db\Migration;

/**
 * Handles adding profile fields to table `{{%user}}`.
 */
class m241203_000000_add_profile_fields_to_user extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'profile_image', $this->string()->after('email'));
        $this->addColumn('{{%user}}', 'theme', $this->string(10)->notNull()->defaultValue('dark')->after('profile_image'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'theme');
        $this->dropColumn('{{%user}}', 'profile_image');
    }
}


