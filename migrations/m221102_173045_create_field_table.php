<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%field}}`.
 */
class m221102_173045_create_field_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
        CREATE TABLE fields(
        id int AUTO_INCREMENT PRIMARY KEY,
        label_ar varchar(50) not null,
        label_en varchar(50) not null,
        name varchar(50) not null,
        type varchar(50) not null,
        status int DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        created_by int not null,
        updated_by int
        
      );");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%field}}');
    }
}
