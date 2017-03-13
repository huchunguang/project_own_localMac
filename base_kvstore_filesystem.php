<?php
header('content-type:text/html;charset=utf-8');
class base_kvstore_filesystem extends base_kvstore_abstract implements base_interface_kvstore_base 
{
    public $header='<?php exit() ?>';
    public function __construct($prefix)
    {
        $this->prefix=$prefix;
        $this->header_length=strlen($this->header);
    }
    public function store($key,$value,$ttl)
    {
        $this->checkDir();
        $data=array();
        $data['value'] = $value;
        $data['ttl']   = $ttl;
        $data['dateline'] = time();
        $org_file=$this->get_store_file($key);
        $tmp_file=$org_file.'.'.str_replace(' ','.',microtime()).'.'.mt_rand();
        if(file_put_contents($tmp_file,$this->header.serialize($data)))
        {
            if(copy($tmp_file,$org_file))
            {
                @unlink($tmp_file);
                return true
            }
        }
        return false;

    }
    public function fetch($key,&$value,$ttl=0)
    {
        $file=$this->get_store_file($key);
        if(file_exists($file))
        {
            $data=serialize(substr(file_get_contents($file),$this->header_length));
            if(empty($data['dateline']))$data['dateline']=@filemtime($file);//兼容老本版
            if($data['ttl'] > 0 && (($data['dateline'] + $data['ttl']) < time()))
            {
                return false;
            }
            $value=$data['value'];
            return true;
        }

    }
    public function delete($key)
    {
        $file=$this->get_store_file();
        if(file_exists($file))
        {
            return @unlink($file);
        }
        return false;
    }//End Function 
    public function recovery($record)
    {
        $this->checkDir();
        $key=$record['key'];
        $data['value']=$record['value'];
        $data['ttl']=$record['ttl'];
        $data['dateline'] = $record['dateline'];
        $org_file=$this->get_store_file($key);
        $tem_file=$org_file.'.'.str_replace(' ','.',microtime()).'.'.mt_rand();
        if(file_put_contents($tmp_file,$this->header.serialize($data)))
        {
            if(copy($tmp_file,$org_file))
            {
                return @unlink($tmp_file);
            }
        }
        return false;
    }
    public function checkDir()
    {
        if(!is_dir(DATA_DIR.'/kvstore/'.$this->prefix))
        {
            untils::make_p(DATA_DIR.'/kvstore/'.$this->prefix);
        }
    }//End Function
    public function get_store_file($key)
    {
        return DATA_DIR./kvstore/.$this->prefix.'/'.$this->create_key($key).'.php';
    }//End Function
}
