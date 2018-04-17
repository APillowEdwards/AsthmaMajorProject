<?php

namespace app\controllers;

use app\models\Dose;

use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

class DoseController extends CustomRestController
{
    public $modelClass = 'app\models\Dose';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        $doses = [];

        foreach ( Dose::find()->all() as $dose ) {
            if ( $dose->medication->user_id == \Yii::$app->user->identity->id ) {
                $doses[] = $dose;
            }
        }

        return $doses;
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
                        // Ensure the user either owns the dose or is an admin
                        'matchCallback' => function ($rule, $action) {
                            return ( \Yii::$app->user->id == Dose::findOne(\Yii::$app->request->get('id'))->medication->user->id ) || Yii::$app->user->identity->isAdmin;
                        },
                    ],
                ],
            ],
        ]);
    }
}
