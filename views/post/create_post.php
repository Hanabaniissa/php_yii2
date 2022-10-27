<?php

use app\models\Category;
use yii\widgets\ActiveForm;

/** @var app\models\post $post */
/** @var app\models\Category $categories */
/** @var yii\web\View $this */

$this->title = 'Create post';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <hr>
    <h1 style=" margin-top: 50px;">Create post</h1>
    <br>

    <div class="container body-content">
        <?php $form = ActiveForm::begin(
            ['options' => ['enctype' => 'multipart/form-data']]); ?>
        <div class="row">
            <div class="for-control">
                <div class="col-lg-6">
                    <?= $form->field($post, 'title'); ?>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="form-groub">
                <div class="col-lg-6">
                    <?= $form->field($post, 'category_id')->dropDownList(\yii\helpers\ArrayHelper::map(Category::getCategories(), 'id', 'label_en'), ['class' => 'form-control']) ?>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="for-control">
                <div class="col-lg-6">
                    <?= $form->field($post, 'description')->textarea(); ?>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="for-control">
                <div class="col-lg-6">
                    <?= $form->field($post, 'phone'); ?>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="for-control">
                <div class="col-lg-6">
                    <?= $form->field($post, 'post_image')->fileInput(); ?>
                </div>
            </div>
        </div>
        <br><br>
        <?= \yii\helpers\Html::submitButton("Post", ['class' => 'btn btn-lg btn-warning', ' style' => 'color: #47555e']) ?>

        <?php ActiveForm::end(); ?>
    </div>

</div>