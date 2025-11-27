<?php

namespace backend\controllers;

use yii\data\ActiveDataProvider;
use common\models\User;
use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use frontend\models\SignupForm;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
{
    return [
        'access' => [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'actions' => ['login', 'error', 'signup'], 
                    'allow' => true,
                ],
                [
                    'actions' => ['logout', 'index'], 
                    'allow' => true,
                    'roles' => ['@'], 
                ],
                [
                    'actions' => ['index'], 
                    'allow' => true,
                    'roles' => ['admin'], 
                ],
            ],
        ],
        'verbs' => [
            'class' => VerbFilter::class,
            'actions' => [
                'logout' => ['post'],
            ],
        ],
    ];
}

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // 1. Obter o gestor de autorização
        $auth = Yii::$app->authManager;
        
        // 2. Encontrar todos os IDs de utilizadores com o papel 'admin'
        // O array retornado é [user_id => RoleObject]
        $adminUserIds = array_keys($auth->getUserIdsByRole('admin'));
        
        // 3. Criar a query: Procurar todos os utilizadores que *NÃO* são admin
        $query = \common\models\User::find()
            ->where(['NOT IN', 'id', $adminUserIds])
            ->orderBy(['created_at' => SORT_DESC]);
            
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12, // Mostrar 12 cartões por página
            ],
        ]);
    
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login() && Yii::$app->user->can('accessBackend')) {
            return $this->goBack();
        }
        else {
            Yii::$app->user->logout();
        }



        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

     public function actionSignup()
    {
        
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            
            return $this->goHome();
            
            
        }

        return $this->render('signup', ['model' => $model, ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    
}
