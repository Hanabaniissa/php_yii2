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
        $this->execute("CREATE TABLE posts(
                      id int AUTO_INCREMENT PRIMARY KEY,
                      title varchar(255) not null ,
                      description varchar(255) not null ,
                      phone int not null ,
                      user_id int not null,
                      category_id int not null,
                      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                      created_by int not null,
                      updated_by int not null ,
                      key `indx_posts_phone` (phone),
                      key `indx_posts_user` (user_id),
                      key `indx_posts_cat` (category_id)

);

CREATE TABLE categories(
                           id int AUTO_INCREMENT PRIMARY KEY,
                           label_ar varchar(50) not null,
                           label_en varchar(50) not null,
                           created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                           updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                           created_by int not null,
                           updated_by int 

);

CREATE TABLE user(
                     id int AUTO_INCREMENT PRIMARY KEY,
                     username varchar(55) not null,
                     password varchar(255) not null,
                     auth_key varchar(255) not null,
                     access_token varchar(255) not null,
                     password_hash varchar(255) not null,
                     password_reset_token varchar(255),
                     status int DEFAULT 10,
                     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                     Updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP        
                     
);


alter table posts add constraint post_user_created_by_fk foreign key (created_by) references user(id);
alter table posts add constraint post_categories_category_id_fk foreign key (category_id) references categories(id);

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
