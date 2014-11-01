<?php

class WargamingCache extends BaseCacheImplementation
{
    /**
     * @var Redis
     */
    public $cache;

    public function setTanks($data)
    {
        $this->cache->set(CacheKey::tanks(), $data);
    }

    public function getTanks()
    {
        return $this->cache->get(CacheKey::tanks());
    }

    public function setTankinfo($tankId, $data)
    {
        if ($tankId && $data) {
            $this->cache->set(CacheKey::tankinfo($tankId), $data);
            return true;
        }
    }

    public function getTankinfo($tankId)
    {
        return $this->cache->get(CacheKey::tankinfo($tankId));
    }

    public function setClanInfo($clanId, $data)
    {
        if ($clanId && $data) {
            $this->cache->set(CacheKey::clanInfo($clanId), $data);
            return true;
        }
    }

    public function getClanInfo($clanId)
    {
        return $this->cache->get(CacheKey::clanInfo($clanId));
    }
} 