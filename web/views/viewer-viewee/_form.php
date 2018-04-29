<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ViewerViewee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="viewer-viewee-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'viewer_id')->textInput() ?>

    <?= $form->field($model, 'viewee_id')->textInput() ?>

    <?= $form->field($model, 'viewer_confirmed')->textInput() ?>

    <?= $form->field($model, 'viewee_confirmed')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
