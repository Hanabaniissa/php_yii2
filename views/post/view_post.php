<?php
/** @var app\models\post $onePost */
/** @var app\models\user $user */
/** @var yii\web\View $this */

dd($onePost);

$this->title = 'Post';
$this->params['breadcrumbs'][] = $this->title;
?>

<script> function ConfirmDelete() {
        return confirm("Are you sure you want to delete?");
    }
</script>


<div style="margin-top: 50px">
    <img src="<?= '/upload/' . $onePost->post_image ?>" style="height: 300px; margin-bottom: 20px">
    <h2 style="margin-top: 10px;"><?php echo \yii\helpers\Html::encode($onePost->title) ?></h2>

    <br>

    <div class="row gap-1">
        <div class="col-md-1 p-2 border">
            <h6>Category</h6>
            <hr>
            <p><?=\yii\helpers\Html::encode($onePost->city->label_en) ?></p>
        </div>

        <div class="col-md-1 p-2 border">
            <h6>SubCategory</h6>
            <hr>
            <p><?=\yii\helpers\Html::encode($onePost->neighborhood->label_en) ?></p>
        </div>
    </div>
    <div class="row gap-1">
    <div class="col-md-1 p-2 border">
        <h6>Category</h6>
        <hr>
        <p><?=\yii\helpers\Html::encode($onePost->category->label_en) ?></p>
    </div>

    <div class="col-md-1 p-2 border">
        <h6>SubCategory</h6>
        <hr>
        <p><?=\yii\helpers\Html::encode($onePost->subCat->label_en) ?></p>
    </div>
    </div>
    <br>
    <div class="row gap-1">
    <?php foreach ($onePost->value as $postValue) : ?>
    <div class="col-md-1 p-2 border">
        <p class="h6"><?= $postValue->field->label_en ?></p>
        <hr>
        <p><?= $postValue->option->label_en ?></p>
    </div>
    <?php endforeach; ?>
    </div>

    <div>
        <div>
            <p class="text-muted" , style="margin-top: 10px;">
                <small>Created at: <?php echo Yii::$app->formatter->asRelativeTime($onePost->created_at) ?>
                </small>
            </p></div>

        <p style="line-height: 2;"><?php echo \yii\helpers\Html::encode($onePost->description) ?></p>
    </div>
    <div>
        <?php echo \yii\helpers\Html::encode($onePost->phone) ?>
    </div>
    <br>
    <hr>

    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->id == $onePost->created_by): ?>
        <div class="row">
            <div class="col-lg-6">
                <?= \yii\helpers\Html::a('Update', \yii\helpers\Url::to(['post/post', 'postId' => $onePost->id]), ['class' => 'btn btn-warning btn-lg', 'style' => 'margin-right: 3px; color: #47555e']) ?>

                <?= \yii\helpers\Html::a('Delete', \yii\helpers\Url::to(['post/delete', 'postId' => $onePost->id, 'id' => $onePost->category_id]), ['class' => 'btn btn-danger btn-lg', 'onclick' => 'ConfirmDelete()']) ?>
            </div>
        </div>
    <?php endif; ?>
</div>
