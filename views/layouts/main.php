<?php

use app\widgets\AlertActualPeriod;
use app\widgets\LabelPeriod;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
use app\controllers\SiteController;

AppAsset::register($this);

$actual_link = urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> - Rejestr</title>
    <link href="/fontawesome/css/all.css" rel="stylesheet">
    <link href="/bootstrap/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <script src="/js/jquery.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?= AlertActualPeriod::widget(); ?>

<div class="row header">
    <div class="col-md-12">

        <div class="col-md-10 col-sm-10 col-xs-7">
            <img src="/logo.png" /><span>Rejestr do rozliczania samochodów</span>
        </div>

        <div class="col-md-2 col-sm-2 col-xs-5 talign-r">
            <?= Yii::$app->user->isGuest ? '' :
            Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Wyloguj',
                ['class' => 'btn btn-danger']
            )
            .Html::endForm();
            ?>
        </div>

        <div class="col-xs-5 barmenu hidden-lg hidden-md hidden-sm">
            <button class="navbar-toggler" style="margin: 10px;" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" >
                <i class="fa fa-bars" aria-hidden="true"></i>
            </button>
        </div>

    </div>
</div>

<div class="row content">

    <div class="menu">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
              <ul class="navbar-nav">

                <?php if(SiteController::accessLogUser(SiteController::ACCESS_DRIVER)){ ?>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-location-arrow" aria-hidden="true"></i> Wyjazdy</a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="<?= Url::to(['/trace/index']) ?>">Lista wyjazdów</a>
                    <a class="dropdown-item" href="<?= Url::to(['/trace/add']) ?>">Dodaj wyjazd</a>
                    <a class="dropdown-item" href="<?= Url::to(['/trace/all']) ?>">Lista wszystkich wyjazdów</a>
                  </div>
                </li>

                <?php if(SiteController::accessLogUser(SiteController::ACCESS_MANAGER)){ ?>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-plug" aria-hidden="true"></i> Tankowania</a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="<?= Url::to(['/refuel/index']) ?>">Lista tankowań</a>
                    <a class="dropdown-item" href="<?= Url::to(['/refuel/add']) ?>">Dodaj tankowanie</a>
                    <a class="dropdown-item" href="<?= Url::to(['/refuel/all']) ?>">Lista wszystkich tankowań</a>
                  </div>
                </li>

                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-phone" aria-hidden="true"></i> Telefony</a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="<?= Url::to(['/phone/index']) ?>">Lista telefonów</a>
                    <a class="dropdown-item" href="<?= Url::to(['/phone/add']) ?>">Dodaj telefon</a>
                  </div>
                </li>

                <?php if(SiteController::accessLogUser(SiteController::ACCESS_ADMIN)){ ?>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> Działy</a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="<?= Url::to(['/section/index']) ?>">Lista działów</a>
                    <a class="dropdown-item" href="<?= Url::to(['/section/add']) ?>">Dodaj dział</a>
                  </div>
                </li>

                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-users" aria-hidden="true"></i> Pracownicy</a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="<?= Url::to(['/employee/index']) ?>">Lista pracowników</a>
                    <a class="dropdown-item" href="<?= Url::to(['/employee/add']) ?>">Dodaj pracownika</a>
                  </div>
                </li>

                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-car" aria-hidden="true"></i> Samochody</a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="<?= Url::to(['/car/index']) ?>">Lista samochodów</a>
                    <a class="dropdown-item" href="<?= Url::to(['/car/add']) ?>">Dodaj samochód</a>
                  </div>
                </li>

                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-calendar-check" aria-hidden="true"></i> Okres rozliczeniowy</a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="<?= Url::to(['/period/index']) ?>">Przełącz okresy rozliczeniowe</a>
                    <a class="dropdown-item" href="<?= Url::to(['/period/close']) ?>">Zamknij okres rozliczeniowy</a>
                  </div>
                </li>

                <li class="nav-item">
                  <a class="nav-link" href="<?= Url::to(['/log/index']) ?>"><i class="fa fa-address-card" aria-hidden="true"></i> Logi</a>
                </li>

                <li class="nav-item">
                  <a class="nav-link" href="<?= Url::to(['/settings/index']) ?>"><i class="fa fa-cog" aria-hidden="true"></i> Ustawienia</a>
                </li>

                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-location-arrow" aria-hidden="true"></i> Statystyka</a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="<?= Url::to(['/statistics/employees']) ?>">Pracownicy</a>
                    <a class="dropdown-item" href="<?= Url::to(['/statistics/cars']) ?>">Samochody</a>
                    <a class="dropdown-item" href="<?= Url::to(['/statistics/refuel']) ?>">Tankowania</a>
                  </div>
                </li>
                <?php }}} ?>

              </ul>
            </div>
        </nav>

    </div>

    <?php if(SiteController::accessLogUser(SiteController::ACCESS_DRIVER)){ ?>
        <div class="col-md-12"><p class="pad10 talign-r">Wybrany okres rozliczeniowy: <?= LabelPeriod::widget(); ?></p></div>
    <?php } ?>

    <div class="col-md-12">
        <h2><?= Html::encode($this->title) ?></h2>
        <?=$content;?>
    </div>

</div>

<div class="row footer">
      <div class="col-md-12">
          <?php if(SiteController::accessLogUser(SiteController::ACCESS_DRIVER)){ ?>
              <a href="/site/set-on-page?count=50&link=<?=$actual_link;?>"><button class="btn btn-sm btn-primary">50 rekordów</button></a>
              <a href="/site/set-on-page?count=100&link=<?=$actual_link;?>"><button class="btn btn-sm btn-primary">100 rekordów</button></a>
              <a href="/site/set-on-page?count=200&link=<?=$actual_link;?>"><button class="btn btn-sm btn-primary">200 rekordów</button></a>
          <?php } ?>
      </div>
</div>

<script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
<?php $this->endBody() ?>



</body>

</html>
<?php $this->endPage() ?>
