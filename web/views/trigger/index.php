<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Triggers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trigger-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Trigger', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    $columns = [
        'id',
        'name',

        ['class' => 'yii\grid\ActionColumn'],
    ];

    if ( Yii::$app->user->identity->isAdmin ) {
        array_unshift( $columns, [
            'header' => 'Owned By',
            'value' => function ($model) {
                return $model->user->username;
            }
        ]);
        array_unshift( $columns, 'id' );
    }

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns,
    ]); ?>
</div>
