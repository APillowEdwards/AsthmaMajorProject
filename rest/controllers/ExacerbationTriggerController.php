<?php

namespace app\controllers;

use app\models\ExacerbationTrigger;

use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

class ExacerbationTriggerController extends CustomRestController
{
    public $modelClass = 'app\models\ExacerbationTrigger';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        $ets = [];

        foreach ( ExacerbationTrigger::find()->all() as $et ) {
            if ( $et->exacerbation->user_id == \Yii::$app->user->identity->id ) {
                $ets[] = $et;
            }
        }

        return $ets;
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
                        // Ensure the user either owns the exacerbation or is an admin
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->isAdmin || ( Yii::$app->user->id == ExacerbationTrigger::findOne(Yii::$app->request->get('id'))->exacerbation->user->id );
                        },
                    ],
                ],
            ],
        ]);
    }
}
