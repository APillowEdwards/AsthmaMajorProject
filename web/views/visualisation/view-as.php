<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $username string */
/* @var $graphs [] */

$this->title = $username . "'s Data";
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_graphs', [
    'graphs' => $graphs,
]) ?>
