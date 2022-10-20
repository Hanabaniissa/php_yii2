<?php

use yii\db\Migration;

/**
 * Class m221020_081314_update_user_post
 */
class m221020_081314_update_user_post extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        return $this->execute("
            alter table posts MODIFY column updated_by int ;
  "
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221020_081314_update_user_post cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221020_081314_update_user_post cannot be reverted.\n";

        return false;
    }
    */
}
