<?php

namespace app\controllers;

use Yii;
use app\models\Dose;
use dektrium\user\models\User;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DoseController implements the CRUD actions for Dose model.
 */
class DoseController extends Controller
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
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                        // Only admins can access the index action
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->isAdmin;
                        }
                    ],
                    [
                        'actions' => ['view', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        // Ensure the user either owns the dose or is an admin
                        'matchCallback' => function ($rule, $action) {
                            return ( Yii::$app->user->id == Dose::findOne(Yii::$app->request->get('id'))->medication->user->id ) || Yii::$app->user->identity->isAdmin;
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
     * Lists all Dose models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Dose::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Dose model.
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
     * Creates a new Dose model.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Dose();
        $steps = [];

        if ( $model->load(Yii::$app->request->post()) && $model->save() ) {
            return $this->redirect(['create']);
        }

        // If Admin, step 1 is choosing the user to create the dose for
        if ( Yii::$app->user->identity->isAdmin ) {
            if ( !Yii::$app->request->post('username') ) {
                $steps['step1'] = true;
                return $this->render('create', [
                    'model' => $model,
                    'steps' => $steps,
                ]);
            } else {
                if ( $user = User::find()->where(['username' => Yii::$app->request->post('username')])->one() ) {
                    $steps['user_id'] = $user->id;
                }
            }
        }

        // Enter the other details
        $steps['step2'] = true;
        return $this->render('create', [
            'model' => $model,
            'steps' => $steps,
        ]);
    }

    /**
     * Updates an existing Dose model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Dose model.
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
     * Finds the Dose model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dose the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Dose::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
