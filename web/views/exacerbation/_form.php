<?php

use app\models\ExacerbationTrigger;
use app\models\Symptom;
use app\models\Trigger;

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
                <label class="btn btn-primary active">
                    <input type="checkbox" autocomplete="off" name="trigger[][name]" value="<?= $trigger->name ?>" <?= ExacerbationTrigger::find()->where(['exacerbation_id' => $model->id, 'trigger_id' => $trigger->id])->exists() ? "checked" : ""?>> <?= $trigger->name ?>
                </label>
            <?php endforeach ?>
        <?php endif ?>
        <a class="btn btn-primary active add-trigger-button" href="#" onclick="$('<label class=&#34;btn btn-primary active&#34; style=&#34;color:#333&#34;><input type=&#34;text&#34; autocomplete=&#34;off&#34; name=&#34;trigger[][name]&#34;></label><span class=&#34;btn btn-primary&#34;onclick=&#34;$(this).prev().remove();$(this).remove()&#34;> X </span>').insertBefore('.add-trigger-button')">Add New Trigger</a>
    </div>

    <h3>Symptoms</h3>
    <div class="symptoms">
        <?php $symptoms = Symptom::possibleSymptomsAndOptions() ?>
        <?php $count = 0 ?>
        <?php foreach ($symptoms as $symptom => $severities): ?>
            <?php $count++ ?>
            <?php $db_symptom = Symptom::find()->where(['exacerbation_id' => $model->id, 'name' => $symptom]) ?>
            <label class="btn btn-primary active">
                <input type="checkbox" autocomplete="off" name="symptom[<?= $count ?>][name]" value="<?= $symptom ?>" <?= $db_symptom->exists() ? 'checked' : '' ?> onclick="if(this.checked){$('#symptom-rating-<?= $count ?>').show();$('#symptom-rating-<?= $count ?> input').prop('disabled',false);}else{$('#symptom-rating-<?= $count ?>').hide();$('#symptom-rating-<?= $count ?> input').prop('disabled',true);}"> <?= $symptom ?>
            </label>
            <div id="symptom-rating-<?= $count ?>" <?= $db_symptom->exists() ? '' : 'style="display: none;"' ?>>
                <?php if ( $severities ): ?>
                    <br />
                    <?php $first = true ?>
                    <?php foreach ($severities as $severity): ?>
                        <label class="btn btn-primary active">
                            <input type="radio" autocomplete="off" name="symptom[<?= $count ?>][severity]" value="<?= $severity ?>" <?= $db_symptom->exists() ? ($severity == $db_symptom->one()->severity ? 'checked' : '' ) : ($first ? 'checked disabled' : 'disabled') ?>> <?= $severity . ($first ? ' (Least Bad)' : '') . (!next( $severities ) ? ' (Worst)' : '') ?>
                        </label>
                        <?php $first = false ?>
                    <?php endforeach ?>
                <?php else: ?>
                    <input type="hidden" name="symptom[<?= $count ?>][severity]" value="1" <?= $db_symptom->exists() ? '' : 'disabled' ?>>
                <?php endif ?>
            </div>
            <hr />
        <?php endforeach ?>
    </div>

    <br />

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
