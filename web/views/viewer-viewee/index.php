<?php

use yii\helpers\Html;
use yii\helpers\Url;
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

    <?php if ( $viewRequestDataProvider->getTotalCount() > 0 ): ?>
        <h2>Your View Requests</h2>
        <?= GridView::widget([
            'dataProvider' => $viewRequestDataProvider, // Because the current user is the viewee
            'columns' => [
                [
                    'header' => 'Viewer',
                    'value' => function ($model) {
                        return $model->viewer->username;
                    },
                ],
                [
                    'header' => 'Delete Request',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return
                            '<a href="' . Url::toRoute(['viewer-viewee/delete', 'id' => $model->id]) . '" class="btn btn-primary" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this request?" data-method="post">Delete</a>';
                    }
                ]
            ],
        ]); ?>
    <?php endif ?>

    <?php if ( $vieweeRequestDataProvider->getTotalCount() > 0 ): ?>
        <h2>Your Viewee Requests</h2>
        <?= GridView::widget([
            'dataProvider' => $vieweeRequestDataProvider, // Because the current user is the viewee
            'columns' => [
                [
                    'header' => 'Viewee',
                    'value' => function ($model) {
                        return $model->viewee->username;
                    },
                ],
                [
                    'header' => 'Action Request',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return
                            '<a href="' . Url::toRoute(['viewer-viewee/confirm', 'id' => $model->id]) . '" class="btn btn-primary" title="Confirm" aria-label="Confirm" data-pjax="0">Confirm</a>  ' .
                            '<a href="' . Url::toRoute(['viewer-viewee/delete', 'id' => $model->id]) . '" class="btn btn-primary" title="Deny" aria-label="Deny" data-pjax="0" data-confirm="Are you sure you want to deny this request?" data-method="post">Deny</a>';
                    }
                ]
            ],
        ]); ?>
    <?php endif ?>

    <?php if ( $viewerDataProvider->getTotalCount() > 0 ): ?>
        <h2>Your Viewers</h2>
        <?= GridView::widget([
            'dataProvider' => $viewerDataProvider, // Because the current user is the viewee
            'columns' => [
                [
                    'header' => 'Viewer',
                    'value' => function ($model) {
                        return $model->viewer->username;
                    },
                ],
                [
                    'header' => 'Action Request',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return
                            '<a href="' . Url::toRoute(['viewer-viewee/delete', 'id' => $model->id]) . '" class="btn btn-primary" title="Remove" aria-label="Remove" data-pjax="0" data-confirm="Are you sure you want to remove these permissions? The viewer will no longer be able to see your data." data-method="post">Remove</a>';
                    }
                ]
            ],
        ]); ?>
    <?php endif ?>

    <?php if ( $vieweeDataProvider->getTotalCount() > 0 ): ?>
        <h2>Your Viewees</h2>
        <?= GridView::widget([
            'dataProvider' => $vieweeDataProvider, // Because the current user is the viewee
            'columns' => [
                [
                    'header' => 'Viewee',
                    'value' => function ($model) {
                        return $model->viewee->username;
                    },
                ],
                [
                    'header' => 'Action Request',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return
                            '<a href="' . Url::toRoute(['viewer-viewee/delete', 'id' => $model->id]) . '" class="btn btn-primary" title="Remove" aria-label="Remove" data-pjax="0" data-confirm="Are you sure you want to remove these permissions?" data-method="post">Remove</a>';
                    }
                ]
            ],
        ]); ?>
    <?php endif ?>

</div>
