<?php
/** @var Category[] $categories */

use app\models\Category;
use yii\bootstrap5\Html;
use yii\helpers\Url;


?>

<div class="row">
    <span><?= Html::a('Create Post', Url::to('/site/post'), ['class' => 'btn btn-primary btn-lg']) ?> </span>
</div>
<br>
<div class="container container-marg">
    <div class="row">
        <?php foreach ($categories as $category): ?>
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body">
                        <a href="<?= Url::to(['site/view', 'id' => $category->id]) ?>">
                            <h3 class="card-title">
                                <?= $category->label_en ?>
                            </h3>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>



