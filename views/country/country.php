<?php
/** @var Country[] $countries */

use app\models\Category;
use yii\bootstrap5\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'AbuyZ: Country';

?>


<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!--Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>

<style> main > .container {
        padding: 35px 0px 0px;
    }</style>

<script> function SelectCountry() {
        return confirm("First of all, you have to choose a country!");
    }
</script>



<!-- Top Image -->


<div class="carousel-inner">
    <div class="carousel-item active">
        <img src="<?= '/images/Home.jpg' ?>" class="d-block w-100" alt=".."
             style=" margin: -175px auto -100px auto;  filter: brightness(65%);">
        <div class="carousel-caption " style="  text-align: center">
            <h1 style="margin: auto auto 20px;color: #ffffff">Welcome to <span style="color: #40ccff">AbuyZ</span></h1>
            <h3 style="color:#eaeaea">Your preferred online shopping platform</h3>
            <p style="color: #e0e0e0; margin:20px auto 30px auto; width:450px; height: auto  "> Choose the country you
                are located in and browse through our extensive list of new and used products and services categories
                and subcategories. Sell anything with a few steps and in seconds.</p>
        </div>
    </div>
</div>



<!-- Countries Icon-->

<hr>
<div style="text-align: center; align-items: center; ">
    <h4 style="margin: 80px auto auto auto;">Select Country</h4>
    <div class="top-container " style="text-align: center; margin-top: 85px;">
        <div class="row" , style="text-align: center;margin: auto 50px auto">

            <?php foreach ($countries as $country): ?>

                <div class="col-md-3" style="margin: 10px auto 25px">
                    <a href="<?= Url::to(['site/index', 'countryId' => $country->id]) ?>" ,
                       style="color: #2192ff; text-decoration: none" , class="link-dark">
                        <img style="width: 80px; height: auto; margin-bottom: 10px;"
                             src="<?= '/images/' . $country->label_en . '.png' ?>" alt="">
                        <h6 style="color:#47555e;"><?= $country->label_en ?></h6>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>



<!-- Map -->


<div style="align-items:center; text-align:center; background-color: #dce6ff; margin: 180px auto 120px; padding-top: 60px;
    padding-bottom: 30px;">
    <h3 style="margin-bottom: 100px;">We are in <span style="color: #40ccff">8 countries </span>of
        the world</h3>
    <a href="<?= Url::to(['site/index', 'countryId' => $countries[5]->id]) ?>" ,
       style="color: #2192ff; text-decoration: none" , class="link-dark">
        <img style="width: 30px; height: auto; position: relative; bottom: 170px; left:280px"
             src="<?= '/images/location.png' ?>" alt="Canada">
    </a>

    <a href="<?= Url::to(['site/index', 'countryId' => $countries[7]->id]) ?>" ,
       style="color: #2192ff; text-decoration: none" , class="link-dark">
        <img style="width: 30px; height: auto; position: relative; bottom: 110px; left:670px"
             src="<?= '/images/location.png' ?>" alt="Syria">
    </a>

    <a href="<?= Url::to(['site/index', 'countryId' => $countries[6]->id]) ?>" ,
       style="color: #2192ff; text-decoration: none" , class="link-dark">
        <img style="width: 30px; height: auto; position: relative; bottom: 77px; left:680px"
             src="<?= '/images/location.png' ?>" alt="Qatar">
    </a>


    <a href="<?= Url::to(['site/index', 'countryId' => $countries[4]->id]) ?>" ,
       style="color: #2192ff; text-decoration: none" , class="link-dark">
        <img style="width: 30px; height: auto; position: relative; bottom: 135px; left:543px"
             src="<?= '/images/location.png' ?>" alt="Croatia">
    </a>


    <img style=" margin-bottom: 60px;width: 1000px; height: auto" src="<?= '/images/w.png' ?>" alt="map">

    <a href="<?= Url::to(['site/index', 'countryId' => $countries[1]->id]) ?>" ,
       style="color: #2192ff; text-decoration: none" , class="link-dark">
        <img style="width: 30px; height: auto; position: relative; bottom: 80px; right: 470px"
             src="<?= '/images/location.png' ?>" alt="Egypt">
    </a>

    <a href="<?= Url::to(['site/index', 'countryId' => $countries[0]->id]) ?>" ,
       style="color: #2192ff; text-decoration: none" , class="link-dark">
        <img style="width: 30px; height: auto;position: relative; bottom: 92px; right: 462px"
             src="<?= '/images/location.png' ?>" alt="Jordan">
    </a>

    <a href="<?= Url::to(['site/index', 'countryId' => $countries[2]->id]) ?>" ,
       style="color: #2192ff; text-decoration: none" , class="link-dark">
        <img style="width: 30px; height: auto;position: relative;bottom: 158px; right: 611px"
             src="<?= '/images/location.png' ?>" alt="UK">
    </a>


    <a href="<?= Url::to(['site/index', 'countryId' => $countries[3]->id]) ?>" ,
       style="color: #2192ff; text-decoration: none" , class="link-dark">
        <img style="width: 30px; height: auto; position: relative; bottom: 73px; right:480px"
             src="<?= '/images/location.png' ?>" alt="UAE">
    </a>

    <div class="row">

        <div class="col-md-3 " style="margin: 10px auto 10px; color: #0e4b60">
            <h2>50 Million</h2>
            <p style="color: #40ccff">user visits</p>
        </div>

        <div class="col-md-3 " style="margin: 10px auto 10px; color: #0e4b60">
            <h2>2M</h2>
            <p style="color: #40ccff">ad per country</p>
        </div>

        <div class="col-md-3 " style="margin: 10px auto 10px; color: #0e4b60">
            <h2>Best Prices</h2>
            <p style="color: #40ccff">friendly website services</p>
        </div>
    </div>
</div>




<!-- People cards -->


<div style="text-align: center; align-items: center">

    <h3 style="margin-bottom: 90px;">What <span style="color: #40ccff">people</span> are saying ...</h3>
    <div class="container" style="text-align: center; margin-top: 50px; margin-bottom: 100px">
        <div class="row" , style="text-align: center">
            <div class="shadow-lg p-3 mb-5 bg-white rounded col-md-3" style="margin: 10px auto 10px">
                <img style="width: 225px; height: auto" src="<?= '/images/person1.png' ?>" alt="">
                <h5 style="margin: 15px 10px 10px;color:  #0e4b60">Mark S.</h5>
                <p style="color: #595959; padding: 6px 10px">"Really, it's a feasible and profitable marketing way.
                    "</p>

            </div>
            <div class="shadow-lg p-3 mb-5 bg-white rounded col-md-3" style="margin: 10px auto 10px;">
                <img style="width: 225px; height: auto" src="<?= '/images/person2.png' ?>" alt="">
                <h5 style="margin: 15px 10px 10px;color:  #0e4b60">Sarah J.</h5>
                <p style="color: #595959; padding: 6px 10px">" I'm from UK, I would like to say that here I find the
                    best prices and quality. "</p>

            </div>
            <div class="shadow-lg p-3 mb-5 bg-white rounded col-md-3" style="margin: 10px auto 10px">
                <img style="width: 225px; height: auto" src="<?= '/images/person3.png' ?>" alt="">
                <h5 style="margin: 15px 10px 10px;color:  #0e4b60">John M.</h5>
                <p style="color: #595959; padding: 6px 10px">"I highly recommend this website. I have marketed a lot of
                    my products and increased profits through it. "</p>

            </div>
        </div>
    </div>
</div>




<!-- Categories -->


<div style="text-align: center; align-items: center; margin: 120px 20px 150px">

    <h3>In <span style="color: #40ccff">AbuyZ</span> you can ...</h3>

   <!-- <div>
        <img>
        <h6></h6>
        <p></p>
    </div>

    <div>
        <img>
        <h6></h6>
        <p></p>
    </div>

</div>-->





<!-- Buttons -->

<div class="bottom-container"
     style="background-color: #40ccff; padding-top: 50px;text-align: center; align-items: center; padding-bottom: 60px">
    <h3 style="margin:10px auto 45px; color: #ffffff">What are you waiting on? </h3>
    <span style=" text-align: center; margin:10px; width: 30px"><?= Html::a('Post Now', Url::to(['country/get']), ['class' => 'btn btn-light btn-lg', 'style' => 'color: #40ccff; padding:10px 30px', 'onclick' => 'SelectCountry()']) ?> </span>
    <span style="text-align: center; margin:10px;"><?= Html::a('Search Now', Url::to(['country/get']), ['class' => 'btn btn-outline-primary btn-lg', 'style' => 'color: #ffffff; padding:10px 25px', 'onclick' => 'SelectCountry()']) ?> </span>

    <h5 style="margin-top: 26px; color: #ffffff">Just start already!</h5>

</div>





