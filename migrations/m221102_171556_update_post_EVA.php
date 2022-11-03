<?php

use yii\db\Migration;

/**
 * Class m221102_171556_update_post_EVA
 */
class m221102_171556_update_post_EVA extends Migration
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
        price int,
        postValue_id int
          );");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221102_171556_update_post_EVA cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221102_171556_update_post_EVA cannot be reverted.\n";

        return false;
    }
    */
}
