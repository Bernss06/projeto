<?php

namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use common\models\User;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\UserSettingsForm;
use common\models\Colecao;
use common\models\Troca;
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
                'only' => ['logout', 'signup', 'settings'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['settings'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
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
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        // Buscar 3 coleções públicas aleatórias
        $featuredCollections = Colecao::find()
            ->where(['status' => 1]) // Apenas públicas
            ->orderBy(new \yii\db\Expression('RAND()'))
            ->limit(3)
            ->with(['favoritos'])
            ->all();

        // Obter IDs de favoritos se o utilizador estiver autenticado
        $favoriteIds = [];
        if (!Yii::$app->user->isGuest) {
            $favoriteIds = \common\models\ColecaoFavorito::find()
                ->where(['user_id' => Yii::$app->user->id])
                ->select('colecao_id')
                ->column();
        }

        return $this->render('index', [
            'featuredCollections' => $featuredCollections,
            'favoriteIds' => $favoriteIds,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
{
    if (!Yii::$app->user->isGuest) {
        return $this->redirect(['site/dashboard']);
    }

    $model = new \frontend\models\LoginForm();
    if ($model->load(Yii::$app->request->post()) && $model->login()) {
        // ✅ redireciona sempre para o dashboard após login
        return $this->redirect(['site/dashboard']);
    }

    $model->password = '';
    return $this->render('login', [
        'model' => $model,
    ]);
}

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    
    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
             return $this->redirect(['site/dashboard']);
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    /*
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }
    */

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    /*
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    */

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->verifyEmail()) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    /*
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
    */

    public function actionDashboard()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $collections = Colecao::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->with('itens')
            ->orderBy(['updated_at' => SORT_DESC])
            ->all();

        return $this->render('dashboard', [
            'collections' => $collections,
        ]);
    }

    public function actionHistoricotrocas()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $trocas = Troca::find()
            ->where([
                'or',
                ['user_origem_id' => Yii::$app->user->id],
                ['user_destino_id' => Yii::$app->user->id],
            ])
            ->orderBy(['data_troca' => SORT_DESC])
            ->all();


        return $this->render('historicotrocas', [
            'trocas' => $trocas,
        ]);
    }


    public function actionSettings()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        /** @var User $user */
        $user = Yii::$app->user->identity;
        $model = new UserSettingsForm($user);

        if ($model->load(Yii::$app->request->post())) {
            $model->profileImage = \yii\web\UploadedFile::getInstance($model, 'profileImage');
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Configurações atualizadas com sucesso.');
                return $this->redirect(['dashboard']);
            }
        }

        return $this->render('settings', [
            'model' => $model,
            'user' => $user,
        ]);
    }

}
