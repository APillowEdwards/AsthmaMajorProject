<?php

namespace app\controllers;

use app\models\Medication;

use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

class MedicationController extends CustomRestController
{
    public $modelClass = 'app\models\Medication';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        return Medication::findAll(['user_id' => \Yii::$app->user->identity->id]);
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'view', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['view', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        // Ensure the user either owns the medication or is an admin
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->isAdmin || ( Yii::$app->user->id == Medication::findOne(Yii::$app->request->get('id'))->user->id );
                        },
                    ],
                ],
            ],
        ]);
    }
}
