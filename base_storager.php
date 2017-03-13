<?php
header('content-type:text/html;charset=utf-8');
class base_storager
{
    static function get_default_driver()
    {
        return config::get('storager.default','filesystem');
    }
    public function __construct($driver=null)
    {
        if(!$driver)
        {
            $driver = static::get_default_driver();

        }
        $this->class_name ='base_storage_'.$driver;
        $this->worker = new $this->class_name;
    }
    public function upload($fileObject)
    {
        $data= $this->worker->save($fileObject);
        if($data)
        {
            $ident_data=$data['url'].'|'.$data['ident'].'|'.substr($this->class_name,strrpos($this->class_name,'_')+1);
            return $ident_data;
        }
        else
        {
            return false;
        }

    }

}

