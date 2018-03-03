<?php

namespace app\controllers;

use yii\rest\ActiveController;

class RestUserController extends ActiveController
{
    public $modelClass = 'dektrium\user\models\User';
}
