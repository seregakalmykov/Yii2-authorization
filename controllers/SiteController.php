<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class SiteController extends Controller
{

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

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionLogin()
    {
        $session = \Yii::$app->session;
        $session->open();

        if (!Yii::$app->user->isGuest){
            return Yii::$app->response->redirect(['site/cabinet']);
        }
        $model = new LoginForm();
        if ($model->issetBlock($session) && $model->load(Yii::$app->request->post())){
            return $this->render('login', [
                'model' => $model,
                'timeblocked' => $model->getFirstError('timeblocked'),
            ]);
        }
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->render('cabinet',[
                'user' => Yii::$app->user->identity,
            ]);
        }
        return $this->render('login', [
            'model' => $model,
            'timeblocked' => null,
        ]);
    }

    public function actionCabinet(){

        if (!Yii::$app->user->isGuest) {
            return $this->render('cabinet',[
                'user' => Yii::$app->user->identity,
            ]);
        } else {
            return Yii::$app->response->redirect(['site/login']);
        }

    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return Yii::$app->response->redirect(['site/login']);
    }

}
