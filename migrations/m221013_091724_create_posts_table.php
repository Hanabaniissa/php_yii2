<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%posts}}`.
 */
class m221013_091724_create_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
        CREATE TABLE posts(
            id int AUTO_INCREMENT PRIMARY KEY,
            title varchar(255) not null ,
            description varchar(255) not null ,
            phone int not null , 
            user_id int not null,
            category_id int not null,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by int not null,
            updated_by int not null,
            key `indx_phone` (phone),
            key `indx_user` (user_id),
            key `indx_phone` (category_id),
          
        )

     CREATE TABLE categories(
         id int AUTO_INCREMENT PRIMARY KEY,
         label_ar varchar(50) not null,
         label_en varchar(50) not null,   
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
         updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
         created_by int not null,
         updated_by int not null, 
               
     )

      CREATE TABLE user(
          id int AUTO_INCREMENT PRIMARY KEY,
          username varchar(55) not null,
          password varchar(255) not null,
          auth_key varchar(255) not null,
          access_token varchar(255) not null,
          
  
        
      )
        




        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%posts}}');
    }
}
