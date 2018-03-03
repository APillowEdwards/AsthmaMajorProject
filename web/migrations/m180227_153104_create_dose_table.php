<?php

use yii\db\Migration;

/**
 * Handles the creation of table `dose`.
 * Has foreign keys to the tables:
 *
 * - `medication`
 */
class m180227_153104_create_dose_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('dose', [
            'id' => $this->primaryKey(),
            'medication_id' => $this->integer()->notNull(),
            'dose_size' => $this->decimal()->notNull(),
            'taken_at' => $this->dateTime()->notNull(),
        ]);

        // creates index for column `medication_id`
        $this->createIndex(
            'idx-dose-medication_id',
            'dose',
            'medication_id'
        );

        // add foreign key for table `medication`
        $this->addForeignKey(
            'fk-dose-medication_id',
            'dose',
            'medication_id',
            'medication',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `medication`
        $this->dropForeignKey(
            'fk-dose-medication_id',
            'dose'
        );

        // drops index for column `medication_id`
        $this->dropIndex(
            'idx-dose-medication_id',
            'dose'
        );

        $this->dropTable('dose');
    }
}
