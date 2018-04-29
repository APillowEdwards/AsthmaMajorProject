<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ViewerViewee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="viewer-viewee-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if ( Yii::$app->user->identity->isAdmin ): ?>
        <?= Html::beginTag('div', ['class' => 'form-group required']) ?>
            <?= Html::label('Viewee Username') ?>
            <?php if ( $model->viewee ): ?>
                <?= Html::textInput('viewee_username', $model->viewee->username, ['class' => 'form-control']) ?>
            <?php else: ?>
                <?= Html::textInput('viewee_username', '', ['class' => 'form-control']) ?>
            <?php endif ?>

            <?php if ( isset( $errors['viewee_username'] ) ): ?>
                <?= Html::beginTag('div', ['class' => 'help-block']) ?>
                    <?= $errors['viewee_username'] ?>
                <?= Html::endTag('div') ?>
            <?php endif ?>
        <?= Html::endTag('div')?>
    <?php endif ?>

    <?= Html::beginTag('div', ['class' => 'form-group required']) ?>
        <?= Html::label('Viewer Username') ?>
        <?php if ( $model->viewer ): ?>
            <?= Html::textInput('viewer_username', $model->viewer->username, ['class' => 'form-control']) ?>
        <?php else: ?>
            <?= Html::textInput('viewer_username', '', ['class' => 'form-control']) ?>
        <?php endif ?>

        <?php if ( isset( $errors['viewer_username'] ) ): ?>
            <?= Html::beginTag('div', ['class' => 'help-block']) ?>
                <?= $errors['viewer_username'] ?>
            <?= Html::endTag('div') ?>
        <?php endif ?>
    <?= Html::endTag('div')?>

    <label>Do you confirm that the above user will be able to view your data?</label>

    <?= $form->field($model, 'viewee_confirmed')->checkbox() ?>

    <?php if ( Yii::$app->user->identity->isAdmin ): ?>
        <?= $form->field($model, 'viewer_confirmed')->checkbox() ?>
    <?php endif ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
