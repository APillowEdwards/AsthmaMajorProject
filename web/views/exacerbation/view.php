<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Exacerbation */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Exacerbations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exacerbation-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            'happened_at',
            [
                'label' => 'Triggers',
                'value' => call_user_func(function ($exacerbation) {
                    $string = "";
                    foreach( $exacerbation->triggers as $trigger) {
                        $string .= $trigger->name . ", ";
                    }
                    // Remove last comma
                    $string = substr($string, 0, -2);
                    return $string;
                }, $model),
            ],
            [
                'label' => 'Symptoms',
                'format' => 'raw',
                'value' => GridView::widget([
                    'dataProvider' => new ArrayDataProvider([
                        'allModels' => $model->symptoms,
                        'pagination' => false,
                    ]),
                    'columns' => [
                        'name',
                        'severity'
                    ],
                    'layout' => '{items}',
                ]),
            ],
        ],
    ]) ?>

</div>
