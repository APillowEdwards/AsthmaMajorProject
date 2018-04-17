<?php

namespace app\controllers;

use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;

class CustomRestController extends ActiveController
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'authenticator' => [
                'class' => \yii\filters\auth\HttpBasicAuth::className(),
                'auth' => function ($username, $password) {
                    $loginForm = \Yii::createObject(\dektrium\user\models\LoginForm::className());
                    $loginForm->login = $username;
                    $loginForm->password = $password;
                    if ( $loginForm->login() ) {
                        return \dektrium\user\models\User::find()->where(['username' => $username])->one();
                    }
                    return null;
                },
                'except' => ['options'],
            ],
        ]);
    }
}
