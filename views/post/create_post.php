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


<div class="site-index">
    <hr>
    <h1 style=" margin-top: 30px;">Create post</h1>
    <hr style="color: #40ccff">


    <div class="container body-content">
        <?php $form = ActiveForm::begin(
            ['options' => ['enctype' => 'multipart/form-data']]); ?>

        <h3>
            <span class="number">1. </span>Choose section</h3>
        <br>
        <div class="row">
            <div class="col-md-3 form-groub">
                <?= $form->field($post, 'city_id')->dropDownList(ArrayHelper::map(City::getCities($country_id, true), 'id', 'label_en'), ['class' => 'form-control', 'id' => 'city', 'prompt' => 'Select City...']); ?>
            </div>

            <div class="col-md-3 form-groub">
                <?= $form->field($post, 'neighborhood_id')->dropDownList(['prompt' => 'Select Neighborhood...'], ['class' => 'form-control', 'id' => 'nighbor']); ?>
            </div>
        </div>

        <hr class="marg">

        <div class="row">
            <div class="col-md-3 form-groub">
                <?= $form->field($post, 'category_id')->dropDownList(ArrayHelper::map(Category::getCategoriesBy($country_id, true), 'id', 'label_en'), ['class' => 'form-control', 'id' => 'category', 'prompt' => 'Select Category...']); ?>
            </div>

            <div class="col-md-3 form-groub">
                <?= $form->field($post, 'subCategory_id')->dropDownList(['prompt' => 'Select Sub Category...'], ['class' => 'form-control subCat', 'id' => 'subCatField']); ?>
            </div>
        </div>


        <hr class="marg">
        <h3>
            <span class="number">2. </span>Post details</h3>
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
                    <?= $form->field($post, 'price')->textInput(['placeholder' => "price"]); ?>
                </div>
            </div>
        </div>

        <br>

        <hr class="marg">
        <h3>
            <span class="number">3. </span>Add Image</h3>

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
            <span class="number">4. </span>Contact information</h3>
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

