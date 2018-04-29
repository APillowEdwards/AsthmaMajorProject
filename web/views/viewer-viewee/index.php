<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Viewer Viewees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="viewer-viewee-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Add a new Viewer to your Account', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <h2>Your Viewer Requests</h2>

    <?= GridView::widget([
        'dataProvider' => $vieweeDataProvider, // Because the current user is the viewee
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

    <h2>Your Viewee Requests</h2>

    <?= GridView::widget([
        'dataProvider' => $viewerDataProvider, // Because the current user is the viewer
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
