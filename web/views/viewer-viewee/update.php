<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ViewerViewee */

$this->title = 'Update Viewer Viewee: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Viewer Viewees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="viewer-viewee-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
