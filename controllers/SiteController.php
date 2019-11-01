<?php

namespace app\controllers;

use app\models\Card;
use app\models\City;
use app\models\Shop;
use app\models\User;
use DateTime;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
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
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     * @throws \Exception
     */
    public function actionIndex()
    {
        $card = new Card();
        $user = new User();

        $session = Yii::$app->session;

        if (isset($_POST['code_random'])) {
            if ($card->load(Yii::$app->request->post())) {

                $session->setFlash('code_random');
                //получим псевдо случайное 4-х значное число
                 $code_random = substr(random_int(0, 99999), 0, 4);

                 //запишем нужные нам переменные в сессию
                 $session->set('code_rand',$code_random);
                 $session->set('card_number',$card->number);
                $session->set('card_phone',$card->phone);

                return $this->render('index', compact('user', 'card', 'code_random'));
            }
        }

        return $this->render('index', compact('user', 'card'));
    }

    /**
     * Displays homepage.
     *
     * @return string
     * @throws \Exception
     */
    public function actionBonusCard()
    {
        $session = Yii::$app->session;
        $card = new Card();
        if (isset($_POST['ok'])) {

            $code_random_new = (int)Yii::$app->request->post('code_random_new');
            $code_random = $session->get('code_rand');
            $card_number = $session->get('card_number');

            if (isset($code_random_new)){

                if($code_random_new == $code_random) {
                    //найдем карту по номеру

                    $card = Card::find()->where(['number' => $card_number])->one();
                    if (isset($card)) {
                        $session->set('card',$card);
                        Yii::$app->session->setFlash('card_number_ok');

                        return $this->render('bonus-card', compact('card'));
                    }
                }
            }
        }

        if(isset($_POST['pin_ok'])){
            return $this->redirect(['site/profile']);
        }
        return $this->render('bonus-card', compact('card'));
    }

    /**
     * Displays homepage.
     *
     * @return string
     * @throws \Exception
     */
    public function actionProfile()
    {
        $session = Yii::$app->session;
        $card = Card::find()->where(['number' => $session->get('card_number')])->one();
        $user = new User();

        $cities = City::find()->all();

        $session = Yii::$app->session;

        if(isset($_POST['pin_ok'])){
            $card = $session->get('card');
            $pin = $card->pin;

            return $this->render('profile', compact('user', 'card','cities'));
        }

        if(isset($_POST['activity_card'])){

            if($user->load(Yii::$app->request->post())){
                $session->setFlash('profile-ok');

                $dateTime = new DateTime($user->birthday);
                $dateTime = $dateTime->format('Y-m-d');
                $user->birthday = $dateTime;

                $password = $user->password;
                $replay_password = Yii::$app->request->post('replay_password');

                $user->save(false);



                $user_id= Yii::$app->db->lastInsertID;

                $card->user_id = $user_id;
                $card->activity_card = true;

                $card->save(false);

            }
        }
        return $this->render('profile', compact('user', 'card','cities'));
    }
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
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

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
