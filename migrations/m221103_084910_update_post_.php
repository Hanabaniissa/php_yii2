<?php

use yii\db\Migration;

/**
 * Class m221103_084910_update_post_
 */
class m221103_084910_update_post_ extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->execute("   ALTER TABLE posts ADD COLUMN (
        country_id int not null,
        city_id int not null,
        subCategory_id int not null,
        neighborhood_id int not null,
        price int
        
          );

          ALTER TABLE posts ADD INDEX (`country_id`);
          ALTER TABLE posts ADD INDEX (`city_id`);
          ALTER TABLE posts ADD INDEX (`subCategory_id`);
          ALTER TABLE posts ADD INDEX (`neighborhood_id`);
          

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
        echo "m221103_084910_update_post_ cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221103_084910_update_post_ cannot be reverted.\n";

        return false;
    }
    */
}
