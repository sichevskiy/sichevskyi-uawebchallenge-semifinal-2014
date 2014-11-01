<?php

class LinkedIn extends BaseAppComponent
{
    /**
     * @var LinkedInApi
     */
    public $api;

    public function init()
    {
        if (!$this->api) {
            $api = new LinkedInApi(
                array(
                    'api_key' => Yii::app()->params['linkedIn']['apiKey'],
                    'api_secret' => Yii::app()->params['linkedIn']['apiSecret'],
                    'callback_url' => Yii::app()->params['linkedIn']['callbackUrl'],
                )
            );

            $this->api = $api;
        }

        if (isset(Yii::app()->session['linkedInToken'])) {
            $this->api->setAccessToken(Yii::app()->session['linkedInToken']);
        }

        parent::init();
    }

    public function getInfo()
    {
        $info = $this->api->get('/people/~:(first-name,last-name,positions)');

        return $info;
    }

    public function peopleSearch($params)
    {
        $result = $this->api->get('/people-search?last-name=Standish');

        return $result;
    }

    public function getLoginUrl()
    {
        return $this->api->getLoginUrl(
            array(
                LinkedInApi::SCOPE_BASIC_PROFILE,
                LinkedInApi::SCOPE_EMAIL_ADDRESS,
                LinkedInApi::SCOPE_NETWORK,
            )
        );

    }

    public function getAccessToken($code)
    {
        $token = $this->api->getAccessToken($code);

        Yii::app()->session['linkedInToken'] = $token;
    }
} 