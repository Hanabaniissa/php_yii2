<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%postValue}}`.
 */
class m221102_173356_create_postValue_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE post_value(
        id int AUTO_INCREMENT PRIMARY KEY,
        post_id int not null,
        field_id int not null,
        option_id int not null,
        integer_val int,
        string_val varchar(255),
        boolean_val BOOLEAN,
        status int DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        created_by int not null,
        updated_by int,
        
         key `indx_postvalue_fields` (field_id),
         key `indx_postvalue_posts` (post_id),
         key `indx_postvalue_options` (option_id)
        
        
      );");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%postValue}}');
    }
}
