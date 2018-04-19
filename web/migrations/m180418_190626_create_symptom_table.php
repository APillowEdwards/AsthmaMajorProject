<?php

use yii\db\Migration;

/**
 * Handles the creation of table `symptom`.
 * Has foreign keys to the tables:
 *
 * - `exacerbation`
 */
class m180418_190626_create_symptom_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('symptom', [
            'id' => $this->primaryKey(),
            'exacerbation_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'severity' => $this->integer()->notNull(),
        ]);

        // creates index for column `exacerbation_id`
        $this->createIndex(
            'idx-symptom-exacerbation_id',
            'symptom',
            'exacerbation_id'
        );

        // add foreign key for table `exacerbation`
        $this->addForeignKey(
            'fk-symptom-exacerbation_id',
            'symptom',
            'exacerbation_id',
            'exacerbation',
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
            'fk-symptom-exacerbation_id',
            'symptom'
        );

        // drops index for column `exacerbation_id`
        $this->dropIndex(
            'idx-symptom-exacerbation_id',
            'symptom'
        );

        $this->dropTable('symptom');
    }
}
