<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PeakFlow */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="peak-flow-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if ( Yii::$app->user->identity->isAdmin ): ?>
        <?= Html::beginTag('div', ['class' => 'form-group required']) ?>
            <?= Html::label('Owned By') ?>
            <?php if ( $model->user ): ?>
                <?= Html::textInput('username', $model->user->username, ['class' => 'form-control']) ?>
            <?php else: ?>
                <?= Html::textInput('username', '', ['class' => 'form-control']) ?>
            <?php endif ?>

            <?php if ( isset( $errors['username'] ) ): ?>
                <?= Html::beginTag('div', ['class' => 'help-block']) ?>
                    <?= $errors['username'] ?>
                <?= Html::endTag('div') ?>
            <?php endif ?>
        <?= Html::endTag('div')?>
    <?php endif ?>

    <?= $form->field($model, 'value')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
