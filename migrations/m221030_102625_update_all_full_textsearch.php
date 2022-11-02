<?php

use yii\db\Migration;

/**
 * Class m221030_102625_update_all_full_textsearch
 */
class m221030_102625_update_all_full_textsearch extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
        alter table posts add fulltext index (title);
        alter table posts add fulltext index (description);
        alter table posts add fulltext index (title, description);
");


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221030_102625_update_all_full_textsearch cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221030_102625_update_all_full_textsearch cannot be reverted.\n";

        return false;
    }
    */
}
