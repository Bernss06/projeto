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
        // 1. Obter contagens para o dashboard
        $totalUsers = \common\models\User::find()->count();
        $totalCollections = \common\models\Colecao::find()->count();
        $totalItems = \common\models\Item::find()->count();
        
        // 2. Dados para o gráfico: Um gráfico por utilizador (Top 4 utilizadores mais recentes)
        $users = \common\models\User::find()
            ->limit(4)
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        $userChartsData = [];

        foreach ($users as $user) {
            $data = \common\models\Colecao::find()
                ->alias('c')
                ->select(['c.nome', 'COUNT(i.id) as item_count'])
                ->leftJoin(['i' => 'item'], 'i.colecao_id = c.id')
                ->where(['c.user_id' => $user->id])
                ->groupBy(['c.id', 'c.nome'])
                ->orderBy(['item_count' => SORT_DESC])
                ->limit(5)
                ->asArray()
                ->all();
            
            if (!empty($data)) {
                $userChartsData[] = [
                    'username' => $user->username,
                    'data' => $data
                ];
            }
        }

        return $this->render('index', [
            'totalUsers' => $totalUsers,
            'totalCollections' => $totalCollections,
            'totalItems' => $totalItems,
            'userChartsData' => $userChartsData,
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
