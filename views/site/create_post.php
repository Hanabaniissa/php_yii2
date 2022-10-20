<?php

use app\models\Category;
use yii\widgets\ActiveForm;

/** @var app\models\post $post */
/** @var app\models\Category $categories*/



$this->title = 'Create post';
?>
<div class="site-index">

    <h1>Create post</h1>
    <br>

    <div class="container body-content">
        <?php $form = ActiveForm::begin() ?>
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
                    <label>Category Name</label>
                    <select class="form-control" name="category_id">
                        <?php foreach (Category::getCategories() as $category): ?>
                            <option value="<?= $category->id ?>"><?= $category->label_en ?></option>
                        <?php endforeach; ?>
                    </select>
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

        <?= \yii\helpers\Html::submitButton("Post") ?>

        <?php ActiveForm::end(); ?>
    </div>

</div>