<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Exacerbation */

$this->title = 'Create Exacerbation';
$this->params['breadcrumbs'][] = ['label' => 'Exacerbations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exacerbation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
