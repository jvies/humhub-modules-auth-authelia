<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user_auth}}`.
 */
class add_authelia_sid_column_to_user_auth_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user_auth}}', 'authelia_sid', $this->string(36)->after('source_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user_auth}}', 'authelia_sid');
    }
}
