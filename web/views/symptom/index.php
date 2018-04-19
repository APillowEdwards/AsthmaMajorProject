<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Symptoms';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="symptom-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Symptom', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'exacerbation_id',
            'name',
            'severity',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
