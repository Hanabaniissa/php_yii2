<?php

use app\models\Assign;
use app\models\Category;
use app\models\City;
use app\models\Country;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/** @var app\models\post $post */
/** @var app\models\Category $categories */
/** @var yii\web\View $this */
/** @var  Country $country */
/** @var integer $country_id */
/** @var Assign[] $assigns */


$this->title = 'Create post';
$this->params['breadcrumbs'][] = $this->title;

//dd(Yii::$app->language);

?>

<style>
    .marg {
        margin: 40px 0 40px 0;
        color: #40ccff;
        width: 79%;
    }

    h3 {
        margin: 30px 0 20px 0;
        color: #3a3a3a;
        font-size: 26px
    }

    .number {
        color: #40ccff;
        font-size: 29px;
    }

</style>

<!--<div class="col-md-8" style="'direction:--><?php //= Yii::$app->language == 'ar' ? "rtl" : "ltr" ?><!--">-->
<div class="col-md-12" style=";direction:<?= Yii::$app->language == 'ar' ? "rtl" : "ltr" ?>">

    <div class="site-index">
        <hr>
        <h1 style=" margin-top: 30px;"><?= Yii::t('app', 'Create post') ?></h1>
        <!--    <h1 style=" margin-top: 50px;">Create post</h1>-->
        <hr style="color: #40ccff">
        <div class="container body-content">
            <?php $form = ActiveForm::begin(
                ['options' => ['enctype' => 'multipart/form-data']]); ?>
            <h3>
                <span class="number">1. </span><?= Yii::t('app', 'Choose section') ?></h3>
            <br>
            <div class="row">
                <div class="col-md-3 form-groub">
                    <?= $form->field($post, Yii::t('app', 'city_id'))->dropDownList(ArrayHelper::map(City::getCities($country_id, true), 'id', 'label_en'), ['class' => 'form-control', 'id' => 'city', 'prompt' => Yii::t('app', 'Select City...')]); ?>
                </div>

                <div class="col-md-3 form-groub">
                    <?= $form->field($post, 'neighborhood_id')->dropDownList(['prompt' => Yii::t('app', 'Select Neighborhood...')], ['class' => 'form-control', 'id' => 'nighbor']); ?>
                </div>
            </div>
            <hr class="marg">
            <div class="row">
                <div class="col-md-3 form-groub">
                    <?= $form->field($post, 'category_id')->dropDownList(ArrayHelper::map(Category::getCategoriesBy($country_id, true), 'id', 'label'), ['class' => 'form-control', 'id' => 'category', 'prompt' => Yii::t('app', 'Select Category...')]); ?>
                </div>

                <div class="col-md-3 form-groub">
                    <?= $form->field($post, 'subCategory_id')->dropDownList(['prompt' => Yii::t('app', 'Select Sub Category...')], ['class' => 'form-control subCat', 'id' => 'subCatField']); ?>
                </div>
            </div>
            <hr class="marg">
            <h3>
                <span class="number">2. </span><?= Yii::t('app', 'Post details') ?></h3>
            <br>
            <div id="fields">
            </div>
            <br><br>
            <div class="row">
                <div class="for-control">
                    <div class="col-lg-5">
                        <?= $form->field($post, 'title'); ?>
                    </div>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="for-control">
                    <div class="col-lg-5">
                        <?= $form->field($post, 'description')->textarea(); ?>
                    </div>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="for-control">
                    <div class="col-lg-5">
                        <?= $form->field($post, 'price')->textInput(['placeholder' => Yii::t('app',"Price")]); ?>
                    </div>
                </div>
            </div>

            <br>

            <hr class="marg">
            <h3>
                <span class="number">3. </span><?=Yii::t('app','Add Image')?></h3>

            <br>

            <div class="row">
                <div class="for-control">
                    <div class="col-lg-5">
                        <?= $form->field($post, 'post_image')->fileInput()->hint('Please insert post image'); ?>    </div>
                </div>
            </div>

            <br>

            <hr class="marg">
            <h3>
                <span class="number">4. </span><?=Yii::t('app','Contact information')?></h3>
            <br>

            <div class="row">
                <div class="for-control">
                    <div class="col-lg-5">
                        <?= $form->field($post, 'phone', [
                                'template' => '{label} <div class="row"><div class="col-md-5">{input}{error}{hint}</div></div>'
                            ]
                        ); ?>
                    </div>
                </div>
            </div>

            <br>
            <br>

            <?= Html::submitButton("Post", ['class' => 'btn btn-lg btn-warning', ' style' => 'color: #ffffff']) ?>

            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>

<?php

$this->registerJs(<<<JS
      
      $('#category').on('change',function (){
            const categoryId = $(this).val();
            const subCatCont = $(".subCat");
            
            
           
            $.ajax({
            url:'http://yii2-basic.local/sub-categories/test',
            data:{
                category:categoryId
            },
           
           
            success:function (response){
                if(!response)
                    return false;
                subCatCont.html('');
                subCatCont.append('<option selected>Select Sub Category...</option>');
                response.forEach((item)=>{
                    subCatCont.append('<option value=' + item.id + '>'+ item.label_en + '</option>');
                })
            }            
            }) 
      }) 
      JS
    , View::POS_END);

?>


<?php

$this->registerJs(<<<JS
      $('#subCatField').on('change',function (){
            const subCatId = $(this).val();
            const fieldOptions= $("#fields");
            
            $.ajax({
            		dataType: 'html',
            		url:'http://yii2-basic.local/field/get',
            data:{
                subCategory_id:subCatId
             
            },
            success:function (response){
                if (!response){
                    return false;}
                fieldOptions.html('');
                fieldOptions.append(response);
                
            }
            }) 
      }) 
      JS
    , View::POS_END);

?>

<?php
$this->registerJs(<<<JS
$('#city').on('change',function (){
    const cityId=$(this).val();
    const neighId=$('#nighbor');
    
    $.ajax({
    
    url:"http://yii2-basic.local/neighborhood/get",
    data:{
        cityId:cityId
    },
    
    success:function (resopnse){
        if (!resopnse){
            return false;
        }
        neighId.html('');
        neighId.html('<option selected>Select Neighborhood ..</option>');
        
        resopnse.forEach((item)=>{
         neighId.append('<option value=' + item.id + '>'+ item.label_en + '</option>');}
        )
    }
    }) 
})

JS

)

?>

