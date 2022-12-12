<?php

namespace app\models\solr;

use yii\base\Model;

class Category extends Model
{
    public int $id;
    public string $label_ar;
    public string $label_en;
    public int $country_id;
    public int $status;
    public string $created_at;
    public string $updated_at;
    public string $created_by;
    public string $updated_by;

}