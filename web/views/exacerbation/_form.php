<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Exacerbation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="exacerbation-form">

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

    <h3>When did the exacerbation happen?</h3>
    <label class="btn btn-primary active" onclick="$('.happenedAtField').hide();$('.happenedAtField input').val('')">
        <input type="radio" name="type" autocomplete="off" checked> Now
    </label>
    <label class="btn btn-primary active" onclick="$('.happenedAtField').show()">
        <input type="radio" name="type" autocomplete="off"> Other
    </label>

    <br />
    <br />

    <div class="happenedAtField" style="display: none">
        <?= $form->field($model, 'happened_at')->widget(DateTimePicker::className(), [
            'options' => ['placeholder' => 'When did you have the exacerbation?'],
            'convertFormat' => true,
            'pluginOptions' => [
                'todayHighlight' => true,
                'todayBtn' => true,
                'format' => 'dd/nn/yyyy HH:i:ss',
                'startView' => 0,
                'todayHighlight' => true
            ]
        ]);?>
    </div>

    <h3>Triggered by:</h3>

    <div class="triggers">
        <?php if ( !Yii::$app->user->identity->isAdmin ): ?>
            <?php $triggers = Trigger::find()->where(['user_id' => Yii::$app->user->identity->id])->all() ?>
            <?php foreach ($triggers as $trigger): ?>
                <div id="<?= $trigger->id ?>">
                    <label class="btn btn-primary active">
                        <input type="checkbox" name="type" autocomplete="off" name="triggers[][name]" value="<?= $trigger->name ?>"> <?= $trigger->name ?>
                    </label>
                </div>
            <?php endforeach ?>
        <?php endif ?>
    </div>
    <a class="btn btn-primary active" href="#" onclick="$('.triggers').append('<label class=&#34;btn btn-primary active&#34; style=&#34;color:#333&#34;><input type=&#34;text&#34; autocomplete=&#34;off&#34; name=&#34;triggers[][name]&#34;></label>')">Add New Trigger</a>

    <h3>Symptoms</h3>

    <br />

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
