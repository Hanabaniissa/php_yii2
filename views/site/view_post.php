<?php
/** @var app\models\post $onePost */

?>

<div>
    <h3><?php echo \yii\helpers\Html::encode($onePost->title) ?></h3>
    <div>
        <br>
        <?php echo \yii\helpers\Html::encode($onePost->description) ?>
    </div>
    <br>
    <div>
        <?php echo \yii\helpers\Html::encode($onePost->phone) ?>
    </div>
    <br>
    <hr>
    <div class="row">
        <div class="col-lg-6">
            <?= \yii\helpers\Html::a('Update', \yii\helpers\Url::to(['/site/post','postId'=>$onePost->id]), ['class' => 'btn btn-primary btn-lg']) ?> </span>
            <?= \yii\helpers\Html::a('Delete', \yii\helpers\Url::to('/site/post'), ['class' => 'btn btn-danger btn-lg']) ?> </span>
        </div>
    </div>
</div>
