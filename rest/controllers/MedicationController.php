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
	$behaviors = parent::behaviors();
	$behaviors[] = TimestampBehaviour::className();
	return $behaviors;
    }
}
