<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use app\models\ContactForm;
use app\models\LoginForm;
use app\models\RegisterForm;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\base\Security;
use yii\mail\MailerInterface;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;

class SiteController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly MailerInterface $mailer,
        private readonly Security $security,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'index'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->role === 'admin';
                        },
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
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'transparent' => true,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            if ($user->role === 'admin') {
                return $this->redirect(['/site/index']);
            }
            if ($user->role === 'mahasiswa') {
                return $this->redirect(['/mahasiswa/dashboard']);
            }
            return $this->goHome();
        }

        $this->layout = 'blank';
        $model = new LoginForm($this->security);

        if ($model->load($this->request->post()) && $model->login()) {
            $user = Yii::$app->user->identity;

            if ($user->role === 'admin') {
                return $this->redirect(['/site/index']);
            }

            if ($user->role === 'mahasiswa') {
                return $this->redirect(['/mahasiswa/dashboard']);
            }

            return $this->goHome(); 
        }
 
        $model->password = '';
        return $this->render('login', ['model' => $model]);
    }

    /**
     * Register action.
     *
     * @return Response|string
     */
    public function actionRegister(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            if ($user->role === 'admin') {
                return $this->redirect(['/site/index']);
            }
            if ($user->role === 'mahasiswa') {
                return $this->redirect(['/mahasiswa/dashboard']);
            }
            return $this->goHome();
        }

        $this->layout = 'blank';
        $model = new RegisterForm();

        if ($model->load($this->request->post()) && $user = $model->register()) {
            Yii::$app->user->login($user);
            Yii::$app->session->setFlash('success', 'Registrasi berhasil! Selamat datang.');
            return $this->redirect(['/mahasiswa/dashboard']);
        }

        return $this->render('register', ['model' => $model]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact(): Response|string
    {
        $model = new ContactForm();

        $contact = $model->load($this->request->post()) && $model->contact(
            $this->mailer,
            Yii::$app->params['adminEmail'],
            Yii::$app->params['senderEmail'],
            Yii::$app->params['senderName'],
        );

        if ($contact) {
            Yii::$app->session->setFlash(
                'success',
                'Thank you for contacting us. We will respond to you as soon as possible.',
            );

            return $this->refresh();
        }

        return $this->render('contact', ['model' => $model]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout(): string
    {
        return $this->render('about');
    }

    
}
