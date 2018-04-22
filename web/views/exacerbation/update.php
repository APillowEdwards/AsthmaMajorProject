<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Exacerbation */

$this->title = 'Update Exacerbation: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Exacerbations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="exacerbation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>