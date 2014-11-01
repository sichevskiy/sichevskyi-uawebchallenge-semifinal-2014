<?php

class Wargaming extends BaseAppComponent
{
    /**
     * @var WGAPI
     */
    public $wgapi;

    /**
     * @var WargamingCache
     */
    protected $_cache;

    public function init()
    {
        if (!$this->wgapi) {
            $wgapi = new WGAPI(Yii::app()->params['wargamingApplicationId'], 'ru');

            $this->wgapi = $wgapi;
        }

        parent::init();
    }

    public function tanks()
    {
        $cache = $this->_cache->getTanks();
        if ($cache) {
            return $this->getData($cache);
        }

        $response = $this->wgapi->encyclopediaTanks();
        $this->_cache->setTanks($response);

        return $this->getData($response);
    }

    public function tankinfo($tankId)
    {
        $cache = $this->_cache->getTankinfo($tankId);
        if ($cache) {
            return $this->getData($cache);
        }

        $response = $this->wgapi->encyclopediaTankinfo($tankId);
        $this->_cache->setTankinfo($tankId, $response);

        return $this->getData($response);
    }

    public function tanksStats($accountId)
    {
        $response = $this->wgapi->tanksStats($accountId);
        return $this->getData($response);
    }

    public function clanInfo($clanId)
    {
        $cache = $this->_cache->getClanInfo($clanId);
        if ($cache) {
            return $this->getData($cache);
        }

        $response = $this->wgapi->clanInfo($clanId);
        $this->_cache->setClanInfo($clanId, $response);

        return $this->getData($response);
    }

    public function globalwarTop()
    {
        $response = $this->wgapi->globalwarTop();

        return $this->getData($response);
    }

    protected function getData($response)
    {
        if ($response) {
            $data = CJSON::decode($response);

            if (isset($data['data'])) {
                return $data['data'];
            }
        }
    }
}