<?php

use yii\db\Migration;

/**
 * Class m221020_080930_update_user_table
 */
class m221020_080930_update_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE user ADD COLUMN (
        password_hash varchar(255) not null,
        password_reset_token varchar(255),
        status int DEFAULT 10,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        Updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP   
)");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221020_080930_update_user_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221020_080930_update_user_table cannot be reverted.\n";

        return false;
    }
    */
}
