<?php

use yii\db\Migration;

/**
 * Class m221103_074032_update_categories_table
 */
class m221103_074032_update_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
       ALTER TABLE categories ADD COLUMN (
        country_id int not null);
           ALTER TABLE categories ADD INDEX (`country_id`); 
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221103_074032_update_categories_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221103_074032_update_categories_table cannot be reverted.\n";

        return false;
    }
    */
}
