<?php
/*
This class extends the PhpRedis class

https://github.com/nicolasff/phpredis
*/
class RedisCacheComponent extends Redis
{
    public $hostname;
    public $port;
    public $password;
    public $dbIndex;

    public function __construct($hostname = 'localhost', $port = 6379, $password = '', $dbIndex = 0)
    {
        $this->hostname = $hostname;
        $this->port = $port;
        $this->password = $password;
        $this->dbIndex = $dbIndex;
    }

    public function init()
    {
        $this->connect($this->hostname, $this->port);
        if (isset($this->password) && !empty($this->password)) {
            $this->auth($this->password);
        }
        $this->select($this->dbIndex);
        $this->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
    }

    public function flush()
    {
        $this->flushAll();
    }
}
