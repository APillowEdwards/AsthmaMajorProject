<?php

use yii\db\Migration;

/**
 * Handles the creation of table `exacerbation`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m180418_185823_create_exacerbation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('exacerbation', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'happened_at' => $this->dateTime()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-exacerbation-user_id',
            'exacerbation',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-exacerbation-user_id',
            'exacerbation',
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
            'fk-exacerbation-user_id',
            'exacerbation'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-exacerbation-user_id',
            'exacerbation'
        );

        $this->dropTable('exacerbation');
    }
}
