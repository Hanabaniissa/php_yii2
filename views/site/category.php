<?php
/** @var app\models\post $posts */
?>


<?php foreach ($posts as $post): ?>
    <div>
        <h3><?php echo \yii\helpers\Html::encode($post->title)?></h3>
        <div>
            <?php echo \yii\helpers\Html::encode($post->description)?>
        </div>
        <hr>
    </div>
<?php endforeach; ?>
