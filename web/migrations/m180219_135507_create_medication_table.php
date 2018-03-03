<?php

use yii\db\Migration;

/**
 * Handles the creation of table `medication`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m180219_135507_create_medication_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('medication', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'type' => $this->string()->notNull(),
            'amount' => $this->decimal()->notNull(),
            'unit' => $this->string()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-medication-user_id',
            'medication',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-medication-user_id',
            'medication',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-medication-user_id',
            'medication'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-medication-user_id',
            'medication'
        );

        $this->dropTable('medication');
    }
}
