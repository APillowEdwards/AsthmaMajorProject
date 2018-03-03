<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Dose */

$this->title = 'Update Dose: ' . $model->medication . ' at ' . $model->taken_at;
$this->params['breadcrumbs'][] = ['label' => 'Doses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dose-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'steps' => $steps,
    ]) ?>

</div>
