<?php

use yii\db\Migration;

/**
 * Handles the creation of table `peak_flow`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m180428_145746_create_peak_flow_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('peak_flow', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'value' => $this->integer()->notNull(),
            'recorded_at' => $this->dateTime()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-peak_flow-user_id',
            'peak_flow',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-peak_flow-user_id',
            'peak_flow',
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
            'fk-peak_flow-user_id',
            'peak_flow'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-peak_flow-user_id',
            'peak_flow'
        );

        $this->dropTable('peak_flow');
    }
}
