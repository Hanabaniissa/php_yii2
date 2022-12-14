<?php
/** @var ActiveDataProvider $posts */
/** @var yii\web\View $this */
/** @var app\models\post $pagesPost */
/** @var string $route */
/** @var app\models\post $facetFields */
/** @var  app\models\post $term */




use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

?>
<?php if (!empty($facetFields)): ?>
    <div style="margin:50px 200px 20px 100px; text-align: center">
        <hr>
        <div class="row g-0">
            <?php foreach ($facetFields as $id => $value): ?>
                <div class="col-md-2">
                    <a style="--bs-link-hover-color: #2192ff; text-decoration: none"
                       href="<?= Url::to(["$route", 'id' => $id]) ?>">
                        <h6><?php echo $value ?></h6>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <hr>
    </div>
<?php endif; ?>

<div style="margin-top: 50px;"></div>
<?php foreach ($posts->models as $post): ?>
    <div class="card-mb-3" style="max-width: 540px;">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="<?= '/upload/' . $post->post_image ?> " class="img-fluid rounded-start"
                     style="margin-right: 40px;">
            </div>
            <div class="col-md-8">
                <div class="card-body " style="padding-left: 30px">
                    <a style="--bs-link-hover-color: #2192ff; text-decoration: none"
                       href="<?= Url::to(['post/view-one', 'id' => $post->id]) ?>">
                        <h3 class="card-title"><?php echo Html::encode($post->title) ?></h3>
                    </a>
                    <p class="card-text"
                       style="padding: 2px;margin-top: 3px;"><?php echo StringHelper::truncateWords(Html::encode($post->description), 40) ?></p>
                    <p class="card-text"><small>Created
                            at: <?php echo Yii::$app->formatter->asDatetime($post->created_at) ?></small></p>
                    <?= Html::a('favorite', Url::to(['favorite/set', 'postId' => $post->id]), ['class' => 'btn btn-primary']) ?>
                    <hr>
                </div>
            </div>
        </div>
    </div>
    <br><br>
<?php endforeach; ?>

<div style="margin-top: 100px; margin-left: 200px;  " class="pagination">
    <?= LinkPager::widget([
        'pagination' => $posts->getPagination(),
    ]) ?>

</div>
