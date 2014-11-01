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

    public function getInfo($user)
    {
        try {
            return $this->api->users->getSingleUser($user);
        } catch (Exception $e) {
        }
    }

    public function getRepositories($user)
    {
        return $this->api->repos->listUserRepositories($user);
    }
} 