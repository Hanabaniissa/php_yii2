<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Json;

class Solr extends Model
{



    public static function getPostKeys($id)
    {
        $post_id = $id;
        $attributes['id'] = $post_id;

        $post = post::find()->where(['id' => $id])->one();
        $countryId = $post->country_id;
        $cityId = $post->city_id;
        $categoryId = $post->category_id;
        $subCategoryId = $post->subCategory_id;

        foreach ($post as $postKeys => $value) {
            $key = $postKeys;
            $key_value = $value;
            $type = gettype($value);
            $field = str_replace('', '_', strtolower($key));
            $field .= "_post_" . self::getFieldTypeForSolr($type);
            $attributes[$field] = $key_value;
        }


        $countries = Country::find()->where(['id' => $countryId])->one();

        foreach ($countries as $country => $value) {
            $key = $country;
            $key_value = $value;
            $type = gettype($value);
            $field = str_replace('', '_', strtolower($key));
            $field .= "_country_" . self::getFieldTypeForSolr($type);
            $attributes[$field] = $key_value;
        }


        $cities = City::find()->where(['id' => $cityId])->one();

        foreach ($cities as $city => $value) {
            $key = $city;
            $key_value = $value;
            $type = gettype($value);
            $field = str_replace('', '_', strtolower($key));
            $field .= "_city_" . self::getFieldTypeForSolr($type);
            $attributes[$field] = $key_value;
        }


        $categories = Category::find()->where(['id' => $categoryId])->one();

        foreach ($categories as $category => $value) {
            $key = $category;
            $key_value = $value;
            $type = gettype($value);
            $field = str_replace('', '_', strtolower($key));
            $field .= "_category_" . self::getFieldTypeForSolr($type);
            $attributes[$field] = $key_value;
        }

        $subCategories = SubCategories::find()->where(['id' => $subCategoryId])->one();

        foreach ($subCategories as $subCategory => $value) {
            $key = $subCategory;
            $key_value = $value;
            $type = gettype($value);
            $field = str_replace('', '_', strtolower($key));
            $field .= "_subCategory_" . self::getFieldTypeForSolr($type);
            $attributes[$field] = $key_value;
        }


        $values = $post->value;

        foreach ($values as $value) {
            $field = $value->field;
            $option = $value->option;
            $type = $field->type;
            $field = str_replace(' ', '_', strtolower($field->label_en));
            $field .= "_field_" . self::getFieldTypeForSolr($type);
            $attributes[$field] = $option->label_en;
        }


        $neighborhood = Neighborhood::find()->where(['id' => 1])->one();

        foreach ($neighborhood as $neighborhoodKey => $value) {
            $key = $neighborhoodKey;
            $key_value = $value;
            $type = gettype($value);
            $field = str_replace('', '_', strtolower($key));
            $field .= "_neighborhoodKey _" . self::getFieldTypeForSolr($type);
            $attributes[$field] = $key_value;
        }

        return $attributes;
    }


    private static function getFieldTypeForSolr($field): string
    {
        switch ($field) {
            case 'integer':
                return 'i';
                break;
            case 'string':
                return 's';
                break;
            case 'Int':
                return 'i';
                break;
            case 'String':
                return 's';
            default:
                return 'null';
        }
    }


    public static function set(){

    }


}