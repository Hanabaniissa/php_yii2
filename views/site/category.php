<?php
/** @var app\models\post $posts */


use yii\helpers\Url;

?>


<?php foreach ($posts as $post): ?>
    <div>
        <div>
            <a href="<?= Url::to(['/site/view-post', 'id' => $post->id]) ?>">
                <h3><?php echo \yii\helpers\Html::encode($post->title) ?></h3>
        </div>
        <div>
            <?php echo \yii\helpers\Html::encode($post->description) ?>
        </div>
        <div>
            <?php echo \yii\helpers\Html::encode($post->phone) ?>
        </div>
        <hr>
    </div>
<?php endforeach; ?>
