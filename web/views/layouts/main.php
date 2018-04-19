<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            [
                'label' => 'Medication',
                'url' => ['/medication/index'],
                'visible' => (!Yii::$app->user->isGuest),
            ],
            [
                'label' => 'New Dose',
                'url' => ['/dose/create'],
                'visible' => (!Yii::$app->user->isGuest && !Yii::$app->user->identity->isAdmin),
            ],
            [
                'label' => 'Doses',
                'url' => ['/dose/index'],
                'visible' => (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin),
            ],
            [
                'label' => 'Triggers',
                'url' => ['/trigger/index'],
                'visible' => (!Yii::$app->user->isGuest),
            ],
            [
                'label' => 'Exacerbations',
                'url' => ['/exacerbation/index'],
                'visible' => (!Yii::$app->user->isGuest),
            ],
            [
                'label' => 'Your Profile',
                'url' => ['/user/' . (Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->id)],
                'visible' => (!Yii::$app->user->isGuest),
            ],
            [
                'label' => 'Admin Panel',
                'url' => ['/user/admin/index'],
                'visible' => (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin),
            ],
            [
                'label' => 'Login',
                'url' => ['/user/security/login'],
                'visible' => Yii::$app->user->isGuest,
            ],
            [
                'label' => 'Logout (' . (Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->username . ')'),
                'url' => ['user/security/logout'],
                'linkOptions' => ['data-method' => 'post'],
                'visible' => (!Yii::$app->user->isGuest),
            ],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>

        <?= $content ?>

    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
