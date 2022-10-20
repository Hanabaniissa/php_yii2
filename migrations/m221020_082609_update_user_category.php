<?php

use yii\db\Migration;

/**
 * Class m221020_082609_update_user_category
 */
class m221020_082609_update_user_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        return $this->execute("
        alter table categories MODIFY column updated_by int ;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221020_082609_update_user_category cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221020_082609_update_user_category cannot be reverted.\n";

        return false;
    }
    */
}
