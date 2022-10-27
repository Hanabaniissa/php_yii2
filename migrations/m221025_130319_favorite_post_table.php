<?php

use yii\db\Migration;

/**
 * Class m221025_130319_favorite_post_table
 */
class m221025_130319_favorite_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
        CREATE TABLE favorite(
            id int AUTO_INCREMENT PRIMARY KEY,
            user_id int not null ,
            post_id int not null,
            status int default 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP        
           );     
        
        "
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221025_130319_favorite_post_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221025_130319_favorite_post_table cannot be reverted.\n";

        return false;
    }
    */
}
