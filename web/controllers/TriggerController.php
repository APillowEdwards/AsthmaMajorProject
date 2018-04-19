<?php

namespace app\controllers;

use Yii;
use app\models\Trigger;
use dektrium\user\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * TriggerController implements the CRUD actions for Trigger model.
 */
class TriggerController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
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
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->isAdmin || ( Yii::$app->user->id == Trigger::findOne(Yii::$app->request->get('id'))->user->id );
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Trigger models.
     * @return mixed
     */
    public function actionIndex()
    {
        if ( Yii::$app->user->identity->isAdmin ) {
            $dataProvider = new ActiveDataProvider([
                'query' => Trigger::find(),
            ]);
        } else {
            $dataProvider = new ActiveDataProvider([
                // Only get the user's triggers, and in alphabetical order
                'query' => Trigger::find()->where(['user_id' => Yii::$app->user->id])->orderBy('name'),
            ]);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Trigger model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Trigger model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Trigger();
        $errors = [];

        if ( $model->load(Yii::$app->request->post()) ){
            if ( Yii::$app->request->isPost ) {
                if ( Yii::$app->user->identity->isAdmin ) {
                    $username = Yii::$app->request->post('username');
                    if ( $user = User::find()->where(['username' => $username])->one() ) {
                        $model->user_id = $user->id;
                    } else {
                        if ( $username == '' ) {
                            $errors['username'] = 'Username is a required field.';
                        } else {
                            $errors['username'] = "'" . $username . "' is not a valid username.";
                        }
                        return $this->render('create', [
                            'errors' => $errors,
                            'model' => $model,
                        ]);
                    }
                } else {
                    $model->user_id = Yii::$app->user->id;
                }
            }
            if ( $model->save() ) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'errors' => $errors,
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Trigger model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $errors = [];

        if ( $model->load(Yii::$app->request->post()) ) {
            if ( Yii::$app->request->isPost && Yii::$app->request->post('username') ) {
                if ( Yii::$app->user->identity->isAdmin ) {
                    $username = Yii::$app->request->post('username');
                    if ( $user = User::find()->where(['username' => $username])->one() ) {
                        $model->user_id = $user->id;
                    } else {
                        if ( $username == '' ) {
                            $errors['username'] = 'Username is a required field.';
                        } else {
                            $errors['username'] = "'" . $username . "' is not a valid username.";
                        }
                        return $this->render('update', [
                            'errors' => $errors,
                            'model' => $model,
                        ]);
                    }
                } else {
                    $model->user_id = Yii::$app->user->id;
                }
            }
            if ( $model->save() ) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'errors' => $errors,
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Trigger model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Trigger model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Trigger the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Trigger::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
