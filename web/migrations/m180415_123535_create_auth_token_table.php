<?php

use yii\db\Migration;

/**
 * Handles the creation of table `auth_token`.
 */
class m180415_123535_create_auth_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('auth_token', [
            'id' => $this->primaryKey(),
            'token' => $this->string()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-auth_token-user_id',
            'auth_token',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-auth_token-user_id',
            'auth_token',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-auth_token-user_id',
            'auth_token'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-auth_token-user_id',
            'auth_token'
        );

        $this->dropTable('auth_token');
    }
}
