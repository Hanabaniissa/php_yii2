<?php

use yii\db\Migration;

/**
 * Class m221103_080940_update_posts_index
 */
class m221103_080940_update_posts_index extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
         
          ALTER TABLE posts ADD INDEX (`country_id`);
          ALTER TABLE posts ADD INDEX (`city_id`);
          ALTER TABLE posts ADD INDEX (`subCategory_id`);
          ALTER TABLE posts ADD INDEX (`neighborhood_id`);
          ALTER TABLE posts ADD INDEX (`postValue_id`);

        ");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221103_080940_update_posts_index cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221103_080940_update_posts_index cannot be reverted.\n";

        return false;
    }
    */
}
