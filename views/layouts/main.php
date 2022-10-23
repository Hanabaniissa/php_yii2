<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => '@web/favicon.ico']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!--Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>

<body class="d-flex flex-column h-100" style="background-color: #FDFDF6;">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark fixed-top', 'style' => "background-color: #2192FF;"]
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
            // ['label' => 'Contact', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest
                ? ['label' => 'Login', 'url' => ['/site/login']]
                : '<li class="nav-item">'
                . Html::beginForm(['/site/logout'])
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'nav-link btn btn-link logout']
                )
                . Html::endForm()
                . '</li>',
            Yii::$app->user->isGuest
                ? ['label' => 'Signup', 'url' => ['/site/sign-up']] : '',
            ['label' => 'English', 'url' => ['/site/about']],

        ]

    ]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<fotter id="footer" class="mt-auto py-3" style="background-color: #f5f5f5; text-align: center;">
    <div class="container-fluid">
        <h6 style="color: #8f8f8f;">Contact us</h6>
        <a href="https://ar-ar.facebook.com/"><i class="social-icon fa-brands fa-facebook-f fa-light"  style=" margin: 15px 7px;color: #47555e;"></i></a>
        <a href="https://www.linkedin.com/"><i class="fa-brands fa-linkedin" style=" margin: 15px 7px;color: #47555e;"></i></a>
        <a href="https://www.instagram.com/"><i class="fa-brands fa-instagram" style=" margin: 15px 7px;color: #47555e; "></i></a>
        <a href="https://www.google.com/intl/ar/gmail/about/"> <i class="fa-solid fa-envelope" style=" margin: 15px 7px;color: #47555e"></i></a>
        <div style="color: #47555e; margin-top: 8px">&copy; My Company <?= date('Y') ?></div>
    </div>
</fotter>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
