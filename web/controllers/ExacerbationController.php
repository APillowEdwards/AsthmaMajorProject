<?php

namespace app\controllers;

use Yii;
use app\models\Exacerbation;
use app\models\Trigger;
use dektrium\user\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * ExacerbationController implements the CRUD actions for Exacerbation model.
 */
class ExacerbationController extends Controller
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
                            return Yii::$app->user->identity->isAdmin || ( Yii::$app->user->id == Exacerbation::findOne(Yii::$app->request->get('id'))->user->id );
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
     * Lists all Exacerbation models.
     * @return mixed
     */
    public function actionIndex()
    {
        if ( Yii::$app->user->identity->isAdmin ) {
            $dataProvider = new ActiveDataProvider([
                'query' => Exacerbation::find(),
            ]);
        } else {
            $dataProvider = new ActiveDataProvider([
                // Only get the user's exacerbations, in reverse chronological order
                'query' => Exacerbation::find()->where(['user_id' => Yii::$app->user->id])->orderBy('happened_at DESC'),
            ]);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Exacerbation model.
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
     * Creates a new Exacerbation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Exacerbation;
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
                // Handle symptoms
                if ( $symptoms = Yii::$app->request->post('symptoms') ) {
                    foreach ($symptoms as $symptom) {
                        $s_model = new Symptom;
                        if ( !($s_model->load($symptom) && $s_model->save()) ) {
                            // Handle error
                        }
                    }
                }
                // Handle triggers
                if ( $triggers = Yii::$app->request->post('triggers') ) {
                    echo "<pre>"; print_r(Yii::$app->request->post()); echo "</pre>";
                    foreach ($triggers as $trigger) {
                        $t_model = new Trigger;
                        if ( !($t_model->load($trigger) && $t_model->save()) ) {
                            echo "<pre>"; print_r($model->getErrors()); echo "</pre>";
                        } else {
                            // Create link record
                            $et_model = new ExacerbationTrigger;
                            $et_model->exacerbation_id = $model->id;
                            $et_model->trigger_id = $t_model->id;
                            $et_model->save();
                        }
                    }
                }
                //return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'errors' => $errors,
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Exacerbation model.
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
     * Deletes an existing Exacerbation model.
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
     * Finds the Exacerbation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Exacerbation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Exacerbation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
