<?php
header('content-type:text/html;charset=utf-8');
class 
{
    private $_session_id='';
    private $_session_key='s';
    private $_session_start=false;
    private $_session_expires=60;
    private $_cookie_expires=0;
    private $_session_destoryed=false;
    public function __construct()
    {
        $config=config::get('session');
        $this->_session_key=$config['cookie']?:'s';
        $this->_cookie_expires=$config['lifetime']?:60;
    }
    public function sess_id()
    {
        return $this->_sess_id;
    }
    public function set_sess_id($sess_id)
    {
        return $this->_sess_id=$sess_id;
    }
    public function set_sess_expires($expires)
    {
        return $this->_sess_expires=$expires;
    }
    private function get_cache_key()
    {
        if(!config::get('cache:enable',true))
        {
            return $this->sess_id();
        }
        else
        {
            return 'USER_SESSION:'.$this->sess_id();
        }
    }
    private function get_session()
    {
        if(!config::get('cache.enable',true))
        {
            if(base_kvstore::instance('sessions')->fetch($this->get_cache_key(),$return))
            {
                return $return;
            }
            else
            {
                return array();            }
        }
        else
        {
            if(cacheobject::get($this->get_cache_key(),$return))
            {
                return $return;
            }
            else
            {
                return array();
            }
        }
    }
    private function set_session($value,$ttl)
    {
        if(!config::get('cache.enable',true))
        {
            return base_kvstore::instance('sessions')->store($this->get_cache_key(),$value,$ttl);
        }
        else
        {
            return cacheobject::set($this->get_cache_key(),$value,$ttl+time());
        }
    }
    public function set_cookie_expires($minute)
    {
        $this->_cookie_expires=($minute>0)?$minute:0;
        if(isset($this->sess_id))
        {
            $cookie_path=kernel::base_url();
            $cookie_path=$cookie_path?$cookie_path:'/';
            header(sprintf('Set-Cookie: %s=%s; path=%s; expires=%s; httpOnly;',$this->sess_key,$this->sess_id,$cookie_path,$expires),true);
        }
    }
    public function get_sess_expires()
    {
        return $this->sess_key;
    }
    public function start()
    {
        if($this->_sess_start!==true)
        {
            $cookie_path= kernel::base_url();
            $cookie_path=$cookie_path?$cookie_path:'/';
            if($this->_cookie_expires >0)
            {
                $cookie_expires=sprintf('expires=%s;',gmdate('D, d M Y H:i:s T',time()+$this->_cookie_expires * 60));
            }
            else
            {
                $cookie_expires='';
            }
            if($_COOKIE[$this->sess_key])
            {
                $this->sess_id=$_COOKIE['$this->sess_key'];
                $_SESSION=$this->get_session();
            }
            elseif(!this->sess_id)
            {
                $this->sess_id=$this->gen_session_id();
                $_SESSION=array();
                header(sprintf('Set-Cookie: %s=%s; path=%s; expires=%s; httpOnly;',$this->sess_key,$this->sess_id,$cookie_path,$cookie_expires),true);
            }
            $this->session_start = true;
            register_shutdown_function(array(&$this,'close'));
        }
        return true;
    }
    public function close($writeBack = true)
    {
        if($this->session_start !== true) return false;
        if(strlen($this->sess_id) != 40)
        {
            return false;
        }
        if(!$this->session_start)
        {
            return false;
        }
        $this->session_start=false;
        if($this->_session_destoryed)
        {
            return true;
        }
        else
        {
            try
            {
                return $this->set_session($_SESSION,$this->_sess_expires*60);
            }
            catch(Exception $e)
            {

            }
        }
    }
    public function gen_session_id()
    {

    }
}
