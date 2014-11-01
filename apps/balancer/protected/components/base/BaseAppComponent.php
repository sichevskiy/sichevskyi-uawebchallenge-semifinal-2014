<?php

class BaseAppComponent extends CApplicationComponent
{
    /**
     * Implementation class for interaction with Cache
     *
     * @var string
     */
    public $cacheClass = '';
    /**
     * Name of cache component, that should be use in component
     *
     * @var string
     */
    public $cacheComponent = 'redisCache';

    /**
     * Change the selected database for the redis connection.
     *
     * @var integer
     */
    public $redisDatabase = 0;
    /**
     *
     * @var string
     */
    public $componentsPathAllias = 'application.components';
    /**
     * object that imlements Cache operations
     *
     * @var object
     */
    protected $_cache = null;
    /**
     * Component errors
     *
     * @var array
     */
    public $errors = array();


    public function init()
    {
        $class = get_called_class();
        $reflection = new ReflectionClass($class);
        $path = $reflection->getFileName();
        $exploded = explode(DIRECTORY_SEPARATOR, $path);
        array_pop($exploded);
        $component = end($exploded);
        Yii::import($this->componentsPathAllias . '.' . $component . '.*');
        Yii::import($this->componentsPathAllias . '.' . $component . '.cache.*');
        parent::init();

        if ($this->cacheClass) {
            $this->_cache = new $this->cacheClass(Yii::app()->{$this->cacheComponent});
        }
    }

    /**
     * return object that imlements Cache operations
     *
     * @object CComponent
     */
    public function getCache()
    {
        return $this->_cache;
    }
}