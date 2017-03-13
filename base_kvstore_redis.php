<?php
header('content-type:text/html;charset=utf-8');
class base_kvstore_redis extends base_kvstore_abstract implements base_interface_kvstore_base,base_interface_kvstore_extension{
    static private $_cacheObj;
    public function __construct($prefix)
    {
        $this->prefix=$prefix;
        $this->connect();
    }
    public function connect()
    {
        if(!isset(self::$_cacheObj))
        {
            if(config::get('kvstore.base_kvstore_redis'))
            {
                self::$_cacheObj=new Redis;
                $config=explode(':',config::get('kvstore.base_kvstore_redis'));
                self::$_cacheObj->connect($config[0],$config[1]);
            }
            else
            {
                trigger_error('can\'t load KVSTORE_REDIS_CONFIG,pelase check it ',E_USER_ERROR);
            }
        }
    }
    public function store($key,$value,$ttl=0)
    {
        $store['value']=$value;
        $store['dateline']=time();
        $store['ttl'] = $ttl;
        return self::$_cacheObj->set($this->create_key($key),json_encode($store));
    }
    public function fetch($key,&$value,$timeout_version=null)
    {
        $store=self::$_cacheObj->get($this->create_key($key));
        $store=json_decode($store,true);
        if($store != false)
        {
            if($timeout_version < $store['dateline'])
            {
                if($store['ttl'] >0 && (($store['ttl'] + $store['dateline']) < time()))
                {
                    return false;
                }
                $value=$store['value'];
                return true;
            }
        }
        return false;
    }
    public function delete($key)
    {
        return self::$_cacheObj->delete($this->create_key($key));
    }
    public function recovery($record)
    {
        $key=$record['key'];
        $store['value']=$record['value'];
        $store['dateline']=time();
        $store['ttl']=$record['ttl'];
        return self::$_cacheObj->set($this->create_key($key),json_encode($store));
    }
    public function increment($key,$offset=1)
    {
        return self::$_cacheObj->increment($this->create_key($key),$offset);
    }//End Function 
    public function decrement($key,$offset)
    {
        return self::$_cacheObj->decrement($this->create_key($key),$offset);
    }
}
