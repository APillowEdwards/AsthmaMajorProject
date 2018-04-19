<?php

use yii\db\Migration;

/**
 * Handles the creation of table `exacerbation_trigger`.
 * Has foreign keys to the tables:
 *
 * - `exacerbation`
 * - `trigger`
 */
class m180418_190802_create_exacerbation_trigger_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('exacerbation_trigger', [
            'id' => $this->primaryKey(),
            'exacerbation_id' => $this->integer()->notNull(),
            'trigger_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `exacerbation_id`
        $this->createIndex(
            'idx-exacerbation_trigger-exacerbation_id',
            'exacerbation_trigger',
            'exacerbation_id'
        );

        // add foreign key for table `exacerbation`
        $this->addForeignKey(
            'fk-exacerbation_trigger-exacerbation_id',
            'exacerbation_trigger',
            'exacerbation_id',
            'exacerbation',
            'id',
            'CASCADE'
        );

        // creates index for column `trigger_id`
        $this->createIndex(
            'idx-exacerbation_trigger-trigger_id',
            'exacerbation_trigger',
            'trigger_id'
        );

        // add foreign key for table `trigger`
        $this->addForeignKey(
            'fk-exacerbation_trigger-trigger_id',
            'exacerbation_trigger',
            'trigger_id',
            'trigger',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `exacerbation`
        $this->dropForeignKey(
            'fk-exacerbation_trigger-exacerbation_id',
            'exacerbation_trigger'
        );

        // drops index for column `exacerbation_id`
        $this->dropIndex(
            'idx-exacerbation_trigger-exacerbation_id',
            'exacerbation_trigger'
        );

        // drops foreign key for table `trigger`
        $this->dropForeignKey(
            'fk-exacerbation_trigger-trigger_id',
            'exacerbation_trigger'
        );

        // drops index for column `trigger_id`
        $this->dropIndex(
            'idx-exacerbation_trigger-trigger_id',
            'exacerbation_trigger'
        );

        $this->dropTable('exacerbation_trigger');
    }
}
