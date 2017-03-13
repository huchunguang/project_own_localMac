<?php
header('content-type:text/html;charset=utf-8');
class base_kvstore_memcache extends base_kvstore_abstract implements base_interface_kvstore_base,base_interface_kvstore_extension
{
    static private $_cacheObj;
    public function __construct($prefix)
    {
        $this->connect();
        $this->prefix();
    }//End Function 
    public function connect()
    {
        if(!isset(self::$_cacheObj))
        {
            $hosts=(array)config::get('kvstore.base_kvstore_memcached.hosts');
            if(!empty($hosts))
            {
                self::$_cacheObj= new Memcached;
                foreach($hosts AS $row)
                {
                    $row=trim($row);
                    if(strpos($row,'unix:///') === 0)
                    {
                        self::$_cacheObj->addServer($row,0);
                    }
                    else
                    {
                        $tmp = explode(':',$row);
                        self::$_cacheObj->addServer($tmp[0],$tmp[1]);
                    }
                }
            }
            else
            {
                trigger_error('kvstore:base_kvstore_memcached hosts is empty,please check it ',E_USER_ERROR);
            }
        }
    }
    public function fetch($key,$value,$timeout_version)
    {
        $store=self::$_cacheObj->get($this->create_key($key));
        if(self::$_cacheObj->getResultCode() != Memcached::RES_NOTFOUND)
        {
            if($timeout_version < $store['dateline'])
            {
                if($store['ttl'] > 0 && (($store['dateline'] + $store['ttl']) < time()))
                {
                    return false;
                }
                $value = $store['value'];
                return true;
            }
        }
    }//End Function
    public function store($key,$value,$ttl=0)
    {
        $store['value'] = $value;
        $store['dateline'] = time();
        $store['ttl']  = $ttl;
        return self::$_cacheObj->set($this->create_key($key),$value);
    }//End Function
    public function delete($key)
    {
        return self::$_cacheObj->delete($this->create_key($key));
    }//End Function
    public function increment($key,$offset=1)
    {
        self::$_cacheObj->get($this->create_key($key));
        if(self::$_cacheObj->getResultCode() == Memcached::RES_NOTFOUND)
        {
            self::$_cacheObj->set($this->create_key($key),0);
        }
        return self::$_cacheObj->increment($this->create_key($key),$offset);
    }//End Function
    public function decrement($key,$offset=1)
    {
        self::$_cacheObj->get($this->create_key($key));
        if(self::$_cacheObj->getResultCode() == Memcached::RES_NOTFOUND)
        {
            self::$_cacheObj->set($this->create_key($key),0);
        }
        return self::$_cacheObj->decrement($this->create_key($key),$offset);
    }
    public function recovery($record)
    {
        $key=$record['key'];
        $store['value']=$record['value'];
        $store['dateline'] =$record['dateline'];
        $store['ttl']=$record['ttl'];
        return self::$_cacheObj->set($this->create_key($key),$store);
    }
}