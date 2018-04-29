<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Viewer-Viewees Admin';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="viewer-viewee-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Viewer Viewee', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'viewer_id',
            'viewee_id',
            'viewer_confirmed',
            'viewee_confirmed',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
