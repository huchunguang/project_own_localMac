<?php
header('content-type:text/html;charset=utf-8');
class base_kvstore_mysql extends base_kvstore_abstract implements base_interface_kvstore_base
{
    public function ($prefix)
    {
        $this->prefix=$prefix;
    }
    public function store($key,$value,$ttl)
    {
        $rows=app::get('base')->model('kvstore')->getList('id',array('prefix'=>$this->prefix,'key'=>$key));
        $data=array('prefix'=>$this->prefix,'key'=>$key,'value'=>$value,'dateline'=>time(),'ttl'=>$ttl);
        if($rows[0]['id'] >0 )
        {
            return app::get('base')->model('kvstore')->update($data,array('prefix'=>$this->prefix,'id'=>$rows[0]['id']));
        }
        else
        {
            return app::get('base')->model('kvstore')->insert($data);
        }
    }
    public function fetch($key,&$value,$timeout_version=null)
    {
        $rows=app::get('base')->database()->excuteQuery('SELECT * FROM `base_kvstore` WHERE `prefix` = ? and `key` =?',[$this->prefix,$key])->fetchAll();
        if($rows[0]['id'] > 0 && $timeout_version < $rows[0]['dateline'])
        {
            if($rows[0]['ttl'] > 0 && ($rows[0]['dateline'] + $rows[0]['ttl'] < time()))
            {
                return false;
            }
            $value=unserialize($rows[0]['value']);
            return true;
        }
        return false;
    }
    public function delete($key)
    {
        return app::get('base')->model('kvstore')->delete(array('prefix'=>$this->prefix,'key'=>$key));
    }
    public function recovery($record)
    {
        return false;
    }
}
