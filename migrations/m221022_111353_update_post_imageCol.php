<?php

use yii\db\Migration;

/**
 * Class m221022_111353_update_post_imageCol
 */
class m221022_111353_update_post_imageCol extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(" ALTER TABLE posts ADD COLUMN (
        post_image varchar(255) not null)
    
     ");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221022_111353_update_post_imageCol cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221022_111353_update_post_imageCol cannot be reverted.\n";

        return false;
    }
    */
}
