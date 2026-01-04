<?php

namespace backend\controllers;

use common\models\User;
use common\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['manageUsers'], // Only authenticated users
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                        'activate' => ['POST'],
                        'deactivate' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id),]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Soft deletes an existing User model by setting status to DELETED.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = User::STATUS_DELETED;
        
        if ($model->save(false)) {
            \Yii::$app->session->setFlash('success', 'User has been soft deleted successfully.');
        } else {
            \Yii::$app->session->setFlash('error', 'Failed to delete user.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Activates a user by setting status to ACTIVE.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        $model->status = User::STATUS_ACTIVE;
        
        if ($model->save(false)) {
            \Yii::$app->session->setFlash('success', 'User has been activated successfully.');
        } else {
            \Yii::$app->session->setFlash('error', 'Failed to activate user.');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Deactivates a user by setting status to INACTIVE.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeactivate($id)
    {
        $model = $this->findModel($id);
        $model->status = User::STATUS_INACTIVE;
        
        if ($model->save(false)) {
            \Yii::$app->session->setFlash('success', 'User has been deactivated successfully.');
        } else {
            \Yii::$app->session->setFlash('error', 'Failed to deactivate user.');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
