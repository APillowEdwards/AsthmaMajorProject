<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Trigger */

$this->title = 'Update Trigger: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Triggers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="trigger-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
