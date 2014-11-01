<?php

class GitHub extends BaseAppComponent
{
    /**
     * @var GitHubClient
     */
    public $api;

    public function init()
    {
        if (!$this->api) {
            $api = new GitHubClient();
            $api->setCredentials(
                Yii::app()->params['gitHub']['username'],
                Yii::app()->params['gitHub']['password']
            );

            $this->api = $api;
        }

        parent::init();
    }

    public function get()
    {

    }
} 