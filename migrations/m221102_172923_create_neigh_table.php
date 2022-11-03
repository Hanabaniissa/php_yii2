<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%neigh}}`.
 */
class m221102_172923_create_neigh_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(" CREATE TABLE neighborhood(
         id int AUTO_INCREMENT PRIMARY KEY,
         label_ar varchar(50) not null,
         label_en varchar(50) not null,
         city_id int not null,
         country_id int not null,
         status int DEFAULT 1,
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
         updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
         created_by int not null,
         updated_by int,
                                  
          key `indx_neighborhood_country` (country_id),  
          key `indx_neighborhood_city` (city_id)        
      );");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%neigh}}');
    }
}
