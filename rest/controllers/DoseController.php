<?php

namespace app\controllers;

use yii\rest\ActiveController;

class DoseController extends ActiveController
{
    public $enableCsrfValidation = false;
    public $modelClass = 'app\models\Dose';

}
