<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\models\Medication;

use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Dose */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dose-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if ( isset( $steps['step1'] ) && $steps['step1'] ): ?>
        <?= Html::beginTag('div', ['class' => 'form-group required']) ?>
            <?= Html::label('Owned By') ?>
            <?php if ( $model->medication ): ?>
                <?= Html::textInput('username', $model->medication->user->username, ['class' => 'form-control']) ?>
            <?php else: ?>
                <?= Html::textInput('username', '', ['class' => 'form-control']) ?>
            <?php endif ?>
        <?= Html::endTag('div') ?>
    <?php endif ?>

    <?php if ( isset( $steps['step2'] ) && $steps['step2'] ): ?>

        <?php $medications = Medication::find()
                ->where(['user_id' => isset( $steps['user_id'] ) ? $steps['user_id'] : Yii::$app->user->id])
                ->orderBy('name')
                ->all();
        ?>

        <?= $form->field($model, 'medication_id')->dropDownList(
                Medication::formatMedicationsForDropDown($medications), ['prompt' => 'Select a Medication']
            ); ?>

        <?= $form->field($model, 'dose_size')->textInput(['maxlength' => true, 'style' => 'width:50%;']) ?>

        <?= $form->field($model, 'taken_at')->widget(DateTimePicker::className(), [
            'options' => ['placeholder' => 'When did you take the medication?'],
            'convertFormat' => true,
            'pluginOptions' => [
                'todayHighlight' => true,
                'todayBtn' => true,
                'format' => 'dd/nn/yyyy HH:i:ss',
                'startView' => 0,
                'todayHighlight' => true
            ]
        ]);?>

    <?php endif ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
