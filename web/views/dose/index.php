<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Doses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dose-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Dose', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'medication_id',
            'dose_size',
            'taken_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
