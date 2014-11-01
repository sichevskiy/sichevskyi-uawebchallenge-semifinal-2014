<?php

ini_set('max_execution_time', 6000);

class SiteController extends Controller
{
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $model = new BalanceForm();

        // if it is ajax validation request
        if (isset($_POST['BalanceForm'])) {
            $model->attributes = $_POST['BalanceForm'];
            if ($model->validate()) {
                $this->redirect(Yii::app()->createUrl('site/balance', array(
                    'clan1Id' => $model->clan1,
                    'clan2Id' => $model->clan2,
                )));
            }
        }

        $clans = Yii::app()->wargaming->globalwarTop();

        $list = array();
        foreach ($clans as $clan) {
            $list[$clan['clan_id']] = $clan['name'];
        }

        $this->render('index', array(
            'model' => $model,
            'list' => $list,
        ));
    }

    public function actionBalance()
    {
        $clan1Id = Yii::app()->request->getQuery('clan1Id');
        $clan2Id = Yii::app()->request->getQuery('clan2Id');

        $clan1Info = Yii::app()->wargaming->clanInfo($clan1Id);
        $clan2Info = Yii::app()->wargaming->clanInfo($clan2Id);

        $clan1 = $this->getBestTanksByClanId($clan1Info, $clan1Id);
        $clan2 = $this->getBestTanksByClanId($clan2Info, $clan2Id);

        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'

        $this->render('result', array(
            'clan1Name' => $clan1Info[$clan1Id]['name'],
            'clan2Name' => $clan2Info[$clan2Id]['name'],
            'clan1DataProvider' => new CArrayDataProvider($clan1, array(
                'pagination' => array('pageSize' => 15),
            )),
            'clan2DataProvider' => new CArrayDataProvider($clan2, array(
                'pagination' => array('pageSize' => 15),
            )),
        ));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                    "Reply-To: {$model->email}\r\n" .
                    "MIME-Version: 1.0\r\n" .
                    "Content-Type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $this->redirect(Yii::app()->user->returnUrl);
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    protected function getBestTankByAccount($account)
    {
        $accountId = $account['account_id'];

        $availableLevels = array(4, 5, 6);

        $tanks = Yii::app()->wargaming->tanks();

        $tankStat = Yii::app()->wargaming->tanksStats($accountId);

        $arr = array();
        foreach ($tankStat[$accountId] as $stats) {
            if (in_array($tanks[$stats['tank_id']]['level'], $availableLevels)) {
                $tankInfo = Yii::app()->wargaming->tankinfo($stats['tank_id']);

                $points = ($tankInfo[$stats['tank_id']]['gun_damage_min'] + $tankInfo[$stats['tank_id']]['gun_damage_max']) / 2 * $tankInfo[$stats['tank_id']]['max_health'];

                $arr[$stats['tank_id']] = array(
                    'id' => $stats['tank_id'],
                    'accountName' => $account['account_name'],
                    'mom' => $stats['mark_of_mastery'],
                    'dmgMin' => $tankInfo[$stats['tank_id']]['gun_damage_min'],
                    'dmgMax' => $tankInfo[$stats['tank_id']]['gun_damage_max'],
                    'hp' => $tankInfo[$stats['tank_id']]['max_health'],
                    'points' => $points,
                    'name' => $tankInfo[$stats['tank_id']]['name_i18n'],
                    'level' => $tanks[$stats['tank_id']]['level'],
                );
            }
        }

        if ($arr) {
            //double sort
            usort($arr, function ($a, $b) {
                if ($a['mom'] > $b['mom']) {
                    return -1;
                }
                if ($a['mom'] < $b['mom']) {
                    return 1;
                }
                if ($a['mom'] == $b['mom']) {
                    if ($a['points'] == $b['points']) {
                        return 0;
                    }
                    return ($a['points'] > $b['points']) ? -1 : 1;
                }
            });

            return $arr[0];
        }
    }

    protected function getBestTanksByClanId($clanInfo, $clanId)
    {
        $arr = array();
        foreach ($clanInfo[$clanId]['members'] as $member) {
            $arr[] = $this->getBestTankByAccount($member);
        }

        //double sort
        usort($arr, function ($a, $b) {
            if ($a['mom'] > $b['mom']) {
                return -1;
            }
            if ($a['mom'] < $b['mom']) {
                return 1;
            }
            if ($a['mom'] == $b['mom']) {
                if ($a['points'] == $b['points']) {
                    return 0;
                }
                return ($a['points'] > $b['points']) ? -1 : 1;
            }
        });

        if (count($arr) > 15) {
            return array_slice($arr, 0, 15);
        } else {
            return $arr;
        }
    }
}