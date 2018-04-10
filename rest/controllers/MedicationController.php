<?php

namespace app\controllers;

use yii\rest\ActiveController;
use yii\behaviors\TimestampBehaviour;

class MedicationController extends ActiveController
{
    public $enableCsrfValidation = false;
    public $modelClass = 'app\models\Medication';

    public function behaviours()
    {
        return ArrayHelper::merge([
            'corsFilter' => [
                'class' => Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                ],
            ],
            'timestampFilter' => [
                'class' => TimestampBehaviour::className(),
            ],
        ], parent::behaviours());
    }
}
