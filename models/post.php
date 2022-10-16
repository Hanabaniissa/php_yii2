<?php

namespace basic\models;


use yii;
use yii\db\ActiveRecord;


/** @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $phone
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property string $category
 */
class post extends ActiveRecord
{

    public static function posts()
    {
        return 'posts';
    }

    public function rules()
    {
        return [
            [[' title', 'description', 'category', 'phone'], "required"],
            ['user_id', 'default', 'value' => \Yii::$app->user->id],
        ];
    }
}


?>