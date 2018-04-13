<?php

namespace app\controllers;

use yii\rest\ActiveController;
use yii\behaviors\TimestampBehaviour;

class MedicationController extends ActiveController
{
    public $enableCsrfValidation = false;
    public $modelClass = 'app\models\Medication';

}
