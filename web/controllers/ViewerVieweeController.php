<?php

namespace app\controllers;

use Yii;
use app\models\ViewerViewee;
use dektrium\user\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * ViewerVieweeController implements the CRUD actions for ViewerViewee model.
 */
class ViewerVieweeController extends Controller
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
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            // Only viewees and admins can edit requests
                            return Yii::$app->user->identity->isAdmin || ( Yii::$app->user->id == ViewerViewee::findOne(Yii::$app->request->get('id'))->viewee->id );
                        },
                    ],
                    [
                        'actions' => ['view', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            // Viewees, the assosiated viewer and admins can delete and view requests
                            return Yii::$app->user->identity->isAdmin || ( Yii::$app->user->id == ViewerViewee::findOne(Yii::$app->request->get('id'))->viewee->id ) || ( Yii::$app->user->id == ViewerViewee::findOne(Yii::$app->request->get('id'))->viewer->id );
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
     * Lists all ViewerViewee models.
     * @return mixed
     */
    public function actionAdmin()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ViewerViewee::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all ViewerViewee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $viewRequestDataProvider = new ActiveDataProvider ([
            'query' => ViewerViewee::find()->where(['viewee_id' => Yii::$app->user->identity->id, 'viewer_confirmed' => false]), // Pending view requests
            'pagination' => false,
        ]);
        $vieweeRequestDataProvider = new ActiveDataProvider ([
            'query' => ViewerViewee::find()->where(['viewer_id' => Yii::$app->user->identity->id, 'viewer_confirmed' => false]), // View requests to be confirmed
            'pagination' => false,
        ]);
        $viewerDataProvider = new ActiveDataProvider ([
            'query' => ViewerViewee::find()->where(['viewee_id' => Yii::$app->user->identity->id, 'viewer_confirmed' => true]), // Confimed viewers
            'pagination' => false,
        ]);
        $vieweeDataProvider = new ActiveDataProvider ([
            'query' => ViewerViewee::find()->where(['viewer_id' => Yii::$app->user->identity->id, 'viewer_confirmed' => true]), // Confirmed viewees
            'pagination' => false,
        ]);

        return $this->render('index', [
            'viewRequestDataProvider' => $viewRequestDataProvider,
            'viewerDataProvider' => $viewerDataProvider,
            'vieweeRequestDataProvider' => $vieweeRequestDataProvider,
            'vieweeDataProvider' => $vieweeDataProvider,
        ]);
    }

    /**
     * Displays a single ViewerViewee model.
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
     * Creates a new ViewerViewee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ViewerViewee();

        $errors = [];

        if ( $model->load(Yii::$app->request->post()) ){
            if ( Yii::$app->request->isPost ) {

                $is_username_error = false;
                // Viewer Username
                $username = Yii::$app->request->post('viewer_username');
                if ( $user = User::find()->where(['username' => $username])->one() ) {
                    $model->viewer_id = $user->id;
                } else {
                    if ( $username == '' ) {
                        $errors['viewer_username'] = 'Username is a required field.';
                    } else {
                        $errors['viewer_username'] = "'" . $username . "' is not a valid username.";
                    }
                    $is_username_error = true;
                }

                if ( Yii::$app->user->identity->isAdmin ) {
                    // Viewee Username
                    $username = Yii::$app->request->post('viewee_username');
                    if ( $user = User::find()->where(['username' => $username])->one() ) {
                        $model->viewee_id = $user->id;
                    } else {
                        if ( $username == '' ) {
                            $errors['viewee_username'] = 'Username is a required field.';
                        } else {
                            $errors['viewee_username'] = "'" . $username . "' is not a valid username.";
                        }
                        $is_username_error = true;
                    }

                } else {
                    $model->viewee_id = Yii::$app->user->id;
                    $model->viewer_confirmed = false;
                }

                if ( $is_username_error ) {
                    return $this->render('create', [
                        'errors' => $errors,
                        'model' => $model,
                    ]);
                }
            }
            if ( $model->save() ) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'errors' => $errors,
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ViewerViewee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $errors = [];

        if ( $model->load(Yii::$app->request->post()) ){
            if ( Yii::$app->request->isPost ) {

                $is_username_error = false;
                // Viewer Username
                $username = Yii::$app->request->post('viewer_username');
                if ( $user = User::find()->where(['username' => $username])->one() ) {
                    $model->viewer_id = $user->id;
                } else {
                    if ( $username == '' ) {
                        $errors['viewer_username'] = 'Username is a required field.';
                    } else {
                        $errors['viewer_username'] = "'" . $username . "' is not a valid username.";
                    }
                    $is_username_error = true;
                }

                if ( Yii::$app->user->identity->isAdmin ) {

                    // Viewee Username
                    $username = Yii::$app->request->post('viewee_username');
                    if ( $user = User::find()->where(['username' => $username])->one() ) {
                        $model->viewee_id = $user->id;
                    } else {
                        if ( $username == '' ) {
                            $errors['viewee_username'] = 'Username is a required field.';
                        } else {
                            $errors['viewee_username'] = "'" . $username . "' is not a valid username.";
                        }
                        $is_username_error = true;
                    }

                } else {
                    $model->viewee_id = Yii::$app->user->id;
                }

                if ( $is_username_error ) {
                    return $this->render('update', [
                        'errors' => $errors,
                        'model' => $model,
                    ]);
                }
            }
            if ( $model->save() ) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'errors' => $errors,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single ViewerViewee model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionConfirm($id)
    {
        $model = $this->findModel($id);

        $model->viewer_confirmed = "1";

        if ( $model->save() ) {
            return $this->redirect(['index']);
        }

        echo "<pre>";print_r($model->getErrors());echo "</pre>";
        //return $this->redirect(['index']);
    }

    /**
     * Deletes an existing ViewerViewee model.
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
     * Finds the ViewerViewee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ViewerViewee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ViewerViewee::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
