<?php

/* @var $this yii\web\View */

$this->title = 'Asthma';

?>
<div class="site-index">
    <div class="jumbotron">
       <h1>Welcome to <?= Yii::$app->name ?>!</h1>
 	   <?php if (Yii::$app->user->isGuest) : ?>
       <p class="lead">Login or register below:</p>
       <p>
         <a class="btn btn-lg btn-success" href="/Asthma/web/web/user/login">Login</a>
         <a class="btn btn-lg btn-success" href="/Asthma/web/web/user/register">Register</a>
      </p>
      <?php endif ?>
    </div>
</div>
