<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Medication */

$this->title = 'Create Medication';
$this->params['breadcrumbs'][] = ['label' => 'Medications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medication-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'errors' => $errors,
        'model' => $model,
    ]) ?>

</div>
