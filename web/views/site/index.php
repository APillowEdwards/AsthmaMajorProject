<?php

/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = 'Asthma';

?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Welcome to <?= Yii::$app->name ?>!</h1>
 	    <?php if (Yii::$app->user->isGuest) : ?>
            <p class="lead">Login or register below:</p>
            <p>
               <a class="btn btn-lg btn-success" href="<?= Url::toRoute(['user/login']) ?>">Login</a>
               <a class="btn btn-lg btn-success" href="<?= Url::toRoute(['user/register']) ?>">Register</a>
            </p>
        <?php else: ?>
            <a class="btn btn-lg btn-success" href="<?= Url::toRoute(['exacerbation/create']) ?>">Record Exacerbation</a>
            <a class="btn btn-lg btn-success" href="<?= Url::toRoute(['dose/create']) ?>">Record Dose</a>
            <a class="btn btn-lg btn-success" href="<?= Url::toRoute(['peak-flow/create']) ?>">Record Peak Flow</a>

        <?php endif ?>
    </div>
</div>
