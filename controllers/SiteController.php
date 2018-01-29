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
use app\redis_lock;

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

    public function actionRead(){
        $disk_lock = new redis_lock\RedisLock();
        $lock_key = 'testLock';
        $lock = $disk_lock->lock($lock_key);
        if (!$lock) return '锁住了！！！！';
        $tmp = [];
        for ($i = 0; $i< 1000000; $i++){
            $tmp[] = rand(0,100);
        }
        $sum = 0;
        foreach ($tmp as $val){
            $sum += $val;
        }
        var_dump($sum);
        $this->voidtest();
        $disk_lock->unlock($lock_key);
    }

    protected function voidtest() : void{
        sleep(3);
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

    public function actionUpload(){
        var_dump(Wechat::upload('./images/test.jpg'));
    }

    public function actionDownload(){{
        Wechat::download('vwqycCDLMAnJ3JzM6jygYlbN5UZUDgZyHgHfnR5V016qcm0XRZvry06_PWsiratm2');
        $content = file_get_contents('http://avatar.csdn.net/D/4/2/1_ljfrocky.jpg');
        var_dump($http_response_header);exit;
        file_put_contents('avatar.jpg', $content);
    }}
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
        $wechatConf = SiteConfigExt::findOne(['config_name' => 'wechat'])->ConfVal;
        $redirectUrl = urlencode('http://wechat.ldc0752.top/wechat/default/auth-response');
        $base_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $wechatConf->appId . '&redirect_uri=' . $redirectUrl . '&response_type=code&scope=snsapi_base&state=snsapi_base#wechat_redirect';
        $userinfo_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $wechatConf->appId . '&redirect_uri=' . $redirectUrl . '&response_type=code&scope=snsapi_userinfo&state=snsapi_userinfo#wechat_redirect';

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
                        ],
                        [
                            'type' => 'view',
                            'name' => '测试主页访问次数',
                            'url' => 'http://wechat.ldc0752.top/wechat/default/test-count',
                        ]
                    ]
                ]
            )
        );
        $status = Wechat::setMenu($menu);
        var_dump($status);exit;
    }

    public function actionTest(){
        var_dump($_SERVER['REMOTE_ADDR']);
    }

    public function actionRbac(){

    }
}
