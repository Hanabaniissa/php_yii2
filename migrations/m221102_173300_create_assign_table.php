<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%assign}}`.
 */
class m221102_173300_create_assign_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(" CREATE TABLE Assign(
        id int AUTO_INCREMENT PRIMARY KEY,
        field_id int not null,
        subCategory_id int not null,
        status int DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        created_by int not null,
        updated_by int,
        
         key `indx_Assign_subCategory` (subCategory_id),
         key `indx_Assign_field` (field_id)
        
      );");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%assign}}');
    }
}
