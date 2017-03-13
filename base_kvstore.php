<?php
header('content-type:text/html;charset=utf-8');
class base_kvstore
{
    static private $__instance = array();
    static private $__persistent = true;
    private $__controller = null;
    private $__prefix=null;
    static public $__fetch_count = 0;
    static public $__store_count = 0;
    static public function get_default_driver()
    {
        return config::get('kvstore.default','base_kvstore_filesystem');
    }
    public function __construct($prefix)
    {
        $driver = static::get_default_driver();
        $this->set_controller(kernel::single($driver,$prefix));
        $this->set_prefix($prefix);
    }
    //End Function
    static public function config_persistent($flag)
    {
        self::$__persistent = ($flag)? true: false;

    }
    //End Function
    static public function kvprefix()
    {
        $prefix= config::get('kvstore.prefix');
        return $prefix? :'bbc-';
    }
    //End Function
    static public function instance($prefix)
    {
        if(!isset(self::$__instance[$prefix]))
        {
            self::$__instance[$prefix]= new base_kvstore($prefix);
        }
        return self::$__instance[$prefix];
    }
    public function set_prefix($prefix)
    {
        $this->__prefix = $prefix;
    }
    public function get_prefix()
    {
        return $this->__prefix;
    }
    public function set_controller($controller``)
    {
        if($controller instanceof base_interface_kvstore_base)
        {
            $this->__controller=$controller;
        }
        else
        {
            throw new RuntimeException('this instance must be implements base_interface_kvstore_base');
        }
    }
    public function get_controller()
    {
        return $this->__controller;
    }
    public function increment($key, $offset =1 )
    {
        if($this->get_controller instanceof base_interface_kvstore_base)
        {
            return $this->get_controller()->increment($key,$offset);
        }
        else
        {
            throw new RuntimeException('this instance can\'t not suppot increment');
        }
    }
    public function decrement($key,$offset)
    {
        if($this->get_controller() instanceof base_interface_kvstore_base)
        {
            return $this->get_controller()->decrement($key,$offset);
        }
        else
        {
            throw new RuntimeException('this instance cant\'t support decrement');
        }
    }
    public function fetch($key,&$value,$timeout_version=null)
    {
        self::$__fetch_count++;
        logger::debug('kvstore:'.self::$__fetch_count.'.'.'instance'.$this->get_prefix().'fetch key:'.$key);
        if($this->get_controller()->fetch($key,$value,$timeout_version))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function store($key,$value,$ttl=0)
    {
        self::$__store_count++;
        $persistent=config::get('kvstore.persistent',true);
        if($persistent && self::$__persistent && get_class($this->get_controller()) !='base_kvstore_mysql' && kernel::is_online())
        {
            $this->persistent($key,$value,$ttl);
        }
        logger::debug('kvstore:'.self::$__fetch_count.'.'.'instance'.$this->get_prefix().'store key:'.$key);
        return $this->get_controller()->store($key,$value,$ttl);
    }
    public function delete($key,$ttl=1)
    {
        if($this->fetch($key,$value))
        {
            return $this->store($key,$value,($ttl>0)?$ttl:1);
        }
        return true;
    }
    //End Function
    public function persistent($key,$value,$ttl=0)
    {
        kernel::single('base_kvstore_mysql',$this->get_prefix())->store($key,$value,$ttl);
    }
    public function recovery($record)
    {
        return $this->get_controller()->recovery($record);
    }
    static public function delete_expire_data()
    {

    }


}
