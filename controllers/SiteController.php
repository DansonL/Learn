<?php

namespace app\controllers;

use app\models_ext\SiteConfigExt;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\common\helpers\Wechat;

class SiteController extends Controller
{
    /**
     * @inheritdoc
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
     * @inheritdoc
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
     */
    public function actionIndex()
    {
        return $this->render('index');
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

    public function actionPass(){
        $config = [
        ];
        $config_value = json_encode($config);
        $model = new SiteConfigExt();
        $model->config_name = 'wechat';
        $model->config_value = $config_value;
        $status = $model->save(false);
        var_dump($status);
    }

    public function actionAccess(){

    }

    public function actionSetMenu(){

        $base_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect';
        $userinfo_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect';
        $menu = array(
            'button' => array(
                [
                    'type' => 'click',
                    'name' => '测试测试',
                    'key' => 'RETEST_TEST'
                ],
                [
                    'type' => 'location_select',
                    'name' => '发送位置',
                    'key' => 'MAP_MAP'
                ],
                [
                    'name' => '菜单',
                    'sub_button' => [
                        [
                            'type' => 'view',
                            'name' => '搜索',
                            'url' => 'https://www.baidu.com'
                        ],
                        [
                            'type' => 'click',
                            'name' => '赞我们一下',
                            'key' => 'LIKE_US'
                        ],
                        [
                            'type' => 'view',
                            'name' => '测试snsapi_base授权',
                            'url' => $base_url
                        ],
                        [
                            'type' => 'view',
                            'name' => '测试snsapi_userinfo授权',
                            'url' => $userinfo_url
                        ]
                    ]
                ]
            )
        );
        $status = Wechat::setMenu($menu);
        var_dump($status);exit;



    }
}
