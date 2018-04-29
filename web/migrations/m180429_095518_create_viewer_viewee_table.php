<?php

use yii\db\Migration;

/**
 * Handles the creation of table `viewer_viewee`.
 * Has foreign keys to the tables:
 *
 * - `user`
 * - `user`
 */
class m180429_095518_create_viewer_viewee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('viewer_viewee', [
            'id' => $this->primaryKey(),
            'viewer_id' => $this->integer()->notNull(),
            'viewee_id' => $this->integer()->notNull(),
            'viewer_confirmed' => $this->boolean()->notNull(),
            'viewee_confirmed' => $this->boolean()->notNull(),
        ]);

        // creates index for column `viewer_id`
        $this->createIndex(
            'idx-viewer_viewee-viewer_id',
            'viewer_viewee',
            'viewer_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-viewer_viewee-viewer_id',
            'viewer_viewee',
            'viewer_id',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `viewee_id`
        $this->createIndex(
            'idx-viewer_viewee-viewee_id',
            'viewer_viewee',
            'viewee_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-viewer_viewee-viewee_id',
            'viewer_viewee',
            'viewee_id',
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
            'fk-viewer_viewee-viewer_id',
            'viewer_viewee'
        );

        // drops index for column `viewer_id`
        $this->dropIndex(
            'idx-viewer_viewee-viewer_id',
            'viewer_viewee'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-viewer_viewee-viewee_id',
            'viewer_viewee'
        );

        // drops index for column `viewee_id`
        $this->dropIndex(
            'idx-viewer_viewee-viewee_id',
            'viewer_viewee'
        );

        $this->dropTable('viewer_viewee');
    }
}
