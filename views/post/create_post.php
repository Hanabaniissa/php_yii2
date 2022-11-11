<?php

use app\models\Category;
use http\Url;

/** @var app\models\post $post */
/** @var app\models\Category $categories */
/** @var yii\web\View $this */
/** @var  \app\models\Country $country */
/** @var integer $country_id */
/** @var \app\models\Assign[] $assigns */



$this->title = 'Create post';
$this->params['breadcrumbs'][] = $this->title;
//$form->field($post, 'subCategory_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\SubCategories::getSubCategories($country_id, 1, true), 'id', 'label_en'), ['class' => 'form-control subCat'])

/*  $form->field($post, 'subCategory_id')->widget(\kartik\depdrop\DepDrop::className(),[
          'options' => ['id'=>'subcategory' ],
      'pluginOptions' => [
              'depends'=>['category'],
             'placeholder'=>'Select Sub Category...',
          'url'=>\yii\helpers\Url::to(['sub-categories/get'])

      ]
  ]);
*/ ?>


    <div class="site-index">
        <hr>
        <h1 style=" margin-top: 30px;">Create post</h1>
        <br>

        <div class="container body-content">
            <?php $form = \yii\bootstrap5\ActiveForm::begin(
                ['options' => ['enctype' => 'multipart/form-data']]); ?>

            <div class="row">
                <div class="form-groub">
                    <div class="col-lg-6">
                        <?= $form->field($post, 'category_id')->dropDownList(\yii\helpers\ArrayHelper::map(Category::getCategoriesBy($country_id, true), 'id', 'label_en'), ['class' => 'form-control', 'id' => 'category', 'prompt' => 'Select Category...']); ?>
                    </div>
                </div>
            </div>

            <br>
            <div class="row">
                <div class="form-groub subcats-cont">
                    <div class="col-lg-6">
                        <label>Sub Category</label>

                        <select class="subCat form-control">

                        </select>
                    </div>
                </div>
            </div>


            <?php foreach ($assigns as $assign):?>

                <br>
                <div class="row">

                        <div class="col-lg-6">
                            <label><?= $assign['label_en'] ?></label>
                            <select class="form-control fields">
                            </select>
                        </div>

                </div>
                <br>
            <?php endforeach; ?>


            <div class="row">
                <div class="form-groub subcats-cont">
                    <div class="col-lg-6">

                    </div>
                </div>
            </div>

            <br>
            <div class="row">
                <div class="for-control">
                    <div class="col-lg-6">
                        <?= $form->field($post, 'title'); ?>
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

            <?php \yii\bootstrap5\ActiveForm::end(); ?>
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
               
                response.forEach((item)=>{
                    subCatCont.append( '<option value=' + item.id + '>'+ item.label_en + '</option>');
                   
                })
               
                    
            }            
            })
            


          
      }) 
      JS
    , \yii\web\View::POS_END);

?>