<?php

namespace app\controllers;

use app\models\Symptom;

use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

class SymptomController extends CustomRestController
{
    public $modelClass = 'app\models\Symptom';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        $symptoms = [];

        foreach ( Symptom::find()->all() as $symptom ) {
            if ( $symptom->exacerbation->user_id == \Yii::$app->user->identity->id ) {
                $symptoms[] = $symptom;
            }
        }

        return $symptoms;
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
                        // Ensure the user either owns the symptom's exacerbation or is an admin
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->isAdmin || ( Yii::$app->user->id == Symptom::findOne(Yii::$app->request->get('id'))->exacerbation->user->id );
                        },
                    ],
                ],
            ],
        ]);
    }
}
