<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ViewerViewee */

$this->title = 'Create Viewer Viewee';
$this->params['breadcrumbs'][] = ['label' => 'Viewer Viewees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="viewer-viewee-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
