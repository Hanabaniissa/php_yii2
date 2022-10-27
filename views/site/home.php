<?php
/** @var Category[] $categories */

use app\models\Category;
use yii\bootstrap5\Html;
use yii\helpers\Url;


?>

<div style="text-align: center;">
<h3 style="margin: 150px auto 80px ;color:#47555e;">Select one of these categories</h3>
<!-- categories -->

<div class="container " style="text-align: center; margin-top: 50px;">
    <div class="row">
        <?php foreach ($categories as $category): ?>
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body"  style="background-color: #9cff2e;">
                        <a href="<?= Url::to(['post/view-by-category', 'id' => $category->id]) ?>" , style="color: #2192ff; text-decoration: none" ,
                           class="link-dark">
                            <h3 class="card-title" style=" color: #47555e">
                                <?= $category->label_en ?>
                            </h3>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<hr style="border: dotted #38e54d 6px; width: 4%; margin: 100px auto 50px; border-bottom: none;">
<!-- button -->
<div class="row">
    <span style="text-align: center; margin-top: 10px;"><?= Html::a('Create Post', Url::to(['post/post']), ['class' => 'btn btn-warning btn-lg','style'=>'color: #47555e']) ?> </span>
</div>
</div>



