<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%subcategory}}`.
 */
class m221103_090539_create_subcategory_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE subCategories(
         id int AUTO_INCREMENT PRIMARY KEY,
         label_ar varchar(50) not null,
         label_en varchar(50) not null,
         country_id int not null,
         category_id int not null,
         status int DEFAULT 1,
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
         updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
         created_by int not null,
         updated_by int,
        
          key `indx_subCategories_cat` (category_id),
          key `indx_subCategories_country` (country_id)  
          
      );");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%subcategory}}');
    }
}
