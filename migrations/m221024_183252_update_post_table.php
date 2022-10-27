<?php

use yii\db\Migration;

/**
 * Class m221024_183252_update_post_table
 */
class m221024_183252_update_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(" ALTER TABLE posts ADD COLUMN (
          status int DEFAULT 10);
     
        ALTER TABLE categories ADD COLUMN (
         status int DEFAULT 10);
     ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221024_183252_update_post_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221024_183252_update_post_table cannot be reverted.\n";

        return false;
    }
    */
}
