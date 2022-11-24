<?php

use yii\db\Migration;

/**
 * Class m221122_082216_user_refresh_tokens_table
 */
class m221122_082216_user_refresh_tokens_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE user_refresh_tokens(
    user_refresh_tokenID INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	urf_userID INT(10) UNSIGNED NOT NULL,
	urf_token VARCHAR(1000) NOT NULL,
	urf_ip VARCHAR(50) NOT NULL,
	urf_user_agent VARCHAR(1000) NOT NULL,
	urf_created DATETIME NOT NULL COMMENT 'UTC',
	PRIMARY KEY (`user_refresh_tokenID`)
       )
       ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221122_082216_user_refresh_tokens_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221122_082216_user_refresh_tokens_table cannot be reverted.\n";

        return false;
    }
    */
}
