<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%option}}`.
 */
class m221102_173154_create_option_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
        CREATE TABLE options(
        id int AUTO_INCREMENT PRIMARY KEY,
        label_ar varchar(50) not null,
        label_en varchar(50) not null,
        title varchar(50) not null,
        field_id int not null,
        status int DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        created_by int not null,
        updated_by int,
         
          key `indx_options_field` (field_id)
        
        
      );
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%option}}');
    }
}
