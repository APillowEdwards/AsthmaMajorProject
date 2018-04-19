<?php

use yii\db\Migration;

/**
 * Handles the creation of table `trigger`.
 */
class m180418_190705_create_trigger_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('trigger', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-trigger-user_id',
            'trigger',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-trigger-user_id',
            'trigger',
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
            'fk-trigger-user_id',
            'trigger'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-trigger-user_id',
            'trigger'
        );

        $this->dropTable('trigger');
    }
}
