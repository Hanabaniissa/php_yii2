<?php
/** @var Category[] $categories */
/** @var \app\models\SubCategories[] $subCategories */

/** @var  \app\models\Country $country */

use app\models\Category;
use yii\bootstrap5\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
$this->title = 'AbuyZ: ' . $country->label_en;
$this->registerJsVar('countryId', Yii::$app->request->get('countryId'));
?>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light text-bg-dark">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <?php foreach ($categories as $category): ?>
                    <li class="nav-item dropdown ">
                        <a class="nav-link" data-role="category" data-category="<?= $category->id ?>"
                           href="<?= Url::to(['site/index', 'categoryId' => $category->id]) ?>"  style="color: #ffffff; text-decoration: none"  class="link-dark"
                           id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= $category->label ?>
                        </a>
                        <div class="dropdown-menu subcats-cont" aria-labelledby="navbarDropdownMenuLink"></div>
                    </li>

                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>


<div style="text-align: center;">
    <h3 style="margin: 120px auto 60px ;color:#47555e;"><?=Yii::t('app','Select one of these categories')?></h3>
    <!-- categories -->

    <div class="container " style="text-align: center; margin-top: 50px;">
        <div class="row"  style="text-align: center">


            <?php foreach ($categories as $category): ?>

                <div class="col-md-3" style="margin: 10px auto 10px">
                    <a href="<?= Url::to(['post/view-by-category', 'id' => $category->id]) ?>"
                       style="color: #2192ff; text-decoration: none"  class="link-dark">
                        <img style="width: 100px; height: auto; margin-bottom: 10px;"
                             src="<?= '/images/' . $category->label_en . '.png' ?>" alt="">
                        <h3 style="color:#47555e;"><?= $category->label ?></h3>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <hr style="border: dotted #38e54d 6px; width: 4%; margin: 40px auto 40px; border-bottom: none;">
    <!-- button -->
    <div class="row">
        <span style="text-align: center; margin-top: 10px;"><?= Html::a(Yii::t('app','Create Post'), Url::to(['post/post']), ['class' => 'btn btn-warning btn-lg', 'style' => 'color: #ffffff']) ?> </span>
    </div>
    <br>

    <?php

    $this->registerJs(<<<JS
$('[data-role="category"]').on('click', function () {
  const categoryId = $(this).data('category');
   // alert(categoryId);
    const subCatCont = $(this).parent().find(".subcats-cont");
    
   console.log(subCatCont);
    $.ajax({
    
        url: 'http://yii2-basic.local/sub-categories/test',
        data: {
           category:categoryId
            },
            
        success: function (response) {
               
            if (!response) return false;
            
            subCatCont.html('');
            response.forEach((item) => {
                subCatCont.append(`<a class="dropdown-item" href="#" style="color: #000000; text-decoration: none" class="link-dark">` + item.label_en + `</a>`);
           } )
        }
        
    });
})
JS


    );

    ?>


