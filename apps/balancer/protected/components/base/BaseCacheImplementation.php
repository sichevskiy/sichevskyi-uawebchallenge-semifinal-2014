<?php

class BaseCacheImplementation extends CComponent
{
    /**
     * @var object
     */
    public $cache;

    /**
     * @var object
     */
    private $defaultCache;

    public function __construct($cacheComponent)
    {
        $this->cache = $cacheComponent;
        $this->defaultCache =  $cacheComponent;
    }

    public function on()
    {
        $this->cache = $this->defaultCache;
    }

    public function off()
    {
        $this->cache = new DummyCacheComponent();
    }
}
