<?php

use yii\db\Migration;

/**
 * Class m180218_215903_add_new_fields_to_profile
 */
class m180218_215903_add_new_fields_to_profile extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('{{%profile}}', 'dob', 'date');
        $this->addColumn('{{%profile}}', 'height', 'decimal');
        $this->addColumn('{{%profile}}', 'weight', 'decimal');
    }

    public function down()
    {
        $this->dropColumn('{{%profile}}', 'dob');
        $this->dropColumn('{{%profile}}', 'height');
        $this->dropColumn('{{%profile}}', 'weight');
    }
}
