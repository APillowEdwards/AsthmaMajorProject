<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Dose */

$this->title = 'Create Dose';
$this->params['breadcrumbs'][] = ['label' => 'Doses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dose-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'steps' => $steps,
    ]) ?>

</div>
