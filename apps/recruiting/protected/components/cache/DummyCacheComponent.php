<?php
/*
This class implements dummy cache
*/

class DummyCacheComponent
{
    public function __call($name, $arguments){}

    public static function __callStatic($name, $arguments){}

    public function __set($name, $value){}

    public function __get($name){}
}