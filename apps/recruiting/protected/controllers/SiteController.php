<?php

ini_set('max_execution_time', 6000);

class SiteController extends Controller
{

    const CANDIDATE_GOOD = 0;
    const CANDIDATE_AVERAGE = 1;
    const CANDIDATE_BAD = 2;
    public $candidateRates = array(
        self::CANDIDATE_GOOD => 'Good',
        self::CANDIDATE_AVERAGE => 'Avarage',
        self::CANDIDATE_BAD => 'Bad',
    );

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
        if (!isset(Yii::app()->session['linkedInToken'])) {
            $this->redirect(Yii::app()->createUrl('site/linkedInLogin'));
        }

        $model = new RecruitingForm();

        $commits = array();
        $graphData = array();
        // if it is ajax validation request
        if (isset($_POST['RecruitingForm'])) {
            $model->attributes = $_POST['RecruitingForm'];
            if ($model->validate()) {
                $commitsData = $this->getCommits($model->username);
                if (!empty($commitsData)) {
                    $commits = new CArrayDataProvider($commitsData, array(
                            'pagination' => array(
                                'pageSize' => 15,
                            ),
                        )
                    );
                }

                $userDetails = array(
                    array(
                        'First Name', 'Last Name', 'Email', 'Phone', 'Username',
                    ),
                    array(
                        $model->firstName,
                        $model->lastName,
                        $model->email,
                        $model->phone,
                        $model->username,
                    ),
                    array(),
                );

                $gitHubDetails = array(
                    array('GitHub latest activity:'),
                    array(
                        'Date', 'Count of commits',
                    ),
                );

                $firstCommit = $commitsData[0]['date'];
                $lastCommit = $commitsData[count($commitsData) - 1]['date'];
                $counts = 0;
                $lastMonthCount = 0;
                foreach ($commitsData as $commitData) {
                    $counts += $commitData['count'];
                    if ($commitData['date'] > ($lastCommit - (60 * 60 * 24 * 30))) {
                        $lastMonthCount += $commitData['count'];
                    }
                }

                $gitHubTotalActivity = ($lastCommit - $firstCommit) / 60 / 60 / 24 / $counts;

                $candidateRateId = self::CANDIDATE_GOOD;
                if (empty($commitsData)) {
                    $candidateRateId = self::CANDIDATE_BAD;
                } else if ($gitHubTotalActivity > 30) {
                    $candidateRateId = self::CANDIDATE_AVERAGE;
                }

                $gitHubActivity = array(
                    array(),
                    array('GitHub commits:'),
                    array('last month', $lastMonthCount),
                    array('Total', $counts),
                    array()
                );

                $candidateRate = array(
                    array('Candidate Rate', $this->candidateRates[$candidateRateId]),
                );

                $gitHubData = array();
                foreach ($commitsData as $commitData) {
                    $gitHubDetails[] = array(
                        date('Y-m-d', $commitData['date']),
                        $commitData['count'],
                    );
                    $gitHubData[] = array(
                        $commitData['count'],
                        date('Y-m-d', $commitData['date']),
                    );
                }

                $graphData = $gitHubData;

                $arrayData = CMap::mergeArray($userDetails, $gitHubDetails);
                $arrayData = CMap::mergeArray($arrayData, $gitHubActivity);
                $arrayData = CMap::mergeArray($arrayData, $candidateRate);

                if (isset($_POST['yt1'])) {
                    $this->download_send_headers("data_export_" . date("Y-m-d") . ".csv");
                    echo $this->array2csv($arrayData);
                    Yii::app()->end();
                }
            }
        }

        $this->render('index', array(
            'model' => $model,
            'commits' => $commits,
            'graphData' => $graphData,
        ));
    }

    public function actionLinkedInLogin()
    {
        $url = Yii::app()->linkedIn->getLoginUrl();

        $this->render('linkedInLogin', array(
            'url' => $url,
        ));
    }

    public function actionLinkedInCallback()
    {
        Yii::app()->linkedIn->getAccessToken($_GET['code']);

        $this->redirect(Yii::app()->createUrl('site/index'));
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

    protected function getCommits($user)
    {
        $info = Yii::app()->gitHub->getInfo($user);
        $data = array();
        if ($info) {
            $dates = array();
            /** @var GitHubSimpleRepo[] $reps */
            $reps = Yii::app()->gitHub->getRepositories($user);
            foreach ($reps as $rep) {
                $commits = Yii::app()->gitHub->api->repos->commits->listCommitsOnRepository($rep->getOwner()->getLogin(), $rep->getName());

                /** @var GitHubCommit[] $commits */
                foreach ($commits as $commit) {
                    $commitDetail = Yii::app()->gitHub->api->git->commits->getCommit($rep->getOwner()->getLogin(), $rep->getName(), $commit->getSha());

                    $time = strtotime($commitDetail->getAuthor()->getDate());
                    $date = date('Y-m-d', $time);
                    $time = strtotime($date);
                    if (!isset($dates[$time])) {
                        $dates[$time] = 1;
                    } else {
                        $dates[$time]++;
                    }
                }
            }

            ksort($dates);
            $id = 0;
            foreach ($dates as $key => $value) {
                $data[] = array(
                    'id' => $id++,
                    'date' => $key,
                    'count' => $value,
                );
            }
        }

        return $data;
    }

    protected function array2csv(array &$array)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        return ob_get_clean();
    }

    function download_send_headers($filename)
    {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }

}