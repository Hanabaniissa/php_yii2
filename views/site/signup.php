<?php

/** @var app\models\SignupForm $model */
/** @var yii\web\View $this */

/** @var yii\bootstrap5\ActiveForm $form */


use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Sign up';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login" >
    <h1 style="margin: 50px auto 20px"><?= Html::encode($this->title) ?></h1>
    <p>Please fill out the following fields to signup:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'Signup-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
            'inputOptions' => ['class' => 'col-lg-3 form-control'],
            'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
        ],
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput() ?>
    <?= $form->field($model, 'password_repeat')->passwordInput() ?>


    <div class="form-group">
        <div style="margin-top: 30px">
            <?= Html::submitButton('Sign up', ['class' => 'btn btn-lg btn-warning',' style'=>'color: #47555e']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

