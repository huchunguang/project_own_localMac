<?php
header('content-type:text/html;charset=utf-8');
class base_static_syscache
{
    static private $__supports=array(
        'service'=>'base_syscache_service',
        'setting'=>'base_syscache_setting'
    );
    static private $__instance=array();
    private $_controller=null;
    private $_supportType=null;
    private $_handler=null;
    static public function get_instance($support_type)
    {
        if(!isset(self::$__supports[$support_type])) return false;
        if(!isset(self::$__supports[$support_type]))
        {
            self::$__supports[$support_type] = new syscache($support_type);
        }
        return self::$__supports[$support_type];
    }
    public function __construct($support_type)
    {
        $this->$_supportType=$support_type;
        $this->_handler=new self::$_supportType[$support_type];
        if($this->_handler instanceof base_interface_syscachce_farmer)
        {
            if(defined('SYSCACHE_ADAPTER'))
            {
                $class_name= constant('SYSCACHE_ADAPTER');
            }
            else
            {
                $class_name= 'base_system_adapter_filesystem';
            }
            $this->set_controller(new $class_name($this->_handler));
            if($this->get_controller()->init() != true)
            {
                $this->_reload();
            }
            return true;
        }
        else
        {
            throw new RuntimeException('this instance must implements base_interface_syscache');
        }
    }//End Function
    public function _reload()
    {
        $this->get_controller()->create($this->_handler>get_data());
        $this->get_controller()->init();
    }
    public function set_controller($controller)
    {
        if($controller instanceof base_interface_syscachce_adapter)
        {
            $this->_controller=$controller;
        }
        else
        {
            throw new RuntimeException('this instance must implements base_interface_syscache_adapter');
        }
    }
    public function get_controller()
    {
        return $this->_controller;
    }
    public function set_last_modify()
    {
        $this->_handler->set_last_modify();
        $this->_reload();
    }
    public function get($key)
    {
        return $this->_controller->get($key);
    }

}
