<?php
class base_setting
{
    public $app;
    public $__source_file_path = null;
    public $__app_conf =array();
    private $_app_setting= array();
    private function __get_source_file_path()
    {
        if(!$this->__source_file_path)
        {
            if(defined('CUSTOM_CORE_DIR') && file_exists(CUSTOM_CORE_DIR.'/'.$this->app->app_id.'/setting.php'))
            {
                $this->__source_file_path=CUSTOM_CORE_DIR.'/'.$this->app->app_id.'/setting.php';
            }
            else
            {
                $this->__source_file_path = $this->app->app_dir.'/setting.php';
            }
        }
        return $this->__source_file_path;
    }

    public function __construct($app)
    {
        $this->app=$app;
    }
    public function &source()
    {
        if(!$this->__app_setting)
        {
            @include($this->__get_source_file_path());
           $this->__app_setting=(array)$setting; 
        }
        return $this->__app_setting;
    }
    public function get_conf($key)
    {
        if(!isset($this->__app_conf[$key]))
        {
            $val = syscache::instance('setting')->get('setting/'.$this->app->app_id.'-'.$key);
            $app_setting = $this->source();
            if($val === null)
            {
                if(!is_null($app_setting) && isset($app_setting[$key]['default']))
                {
                    $val = $app_setting[$key]['default'];
                }
                else
                {
                    return null;
                }

            }
            $this->__app_conf[$key]=$val;
        }
        return $this->__app_conf[$key];
    }
    public function set_conf($key,$value)
    {
        $filter=array('app'=>$this->app->app_id,'key'=>$key);
        $data  =array('app'=>$this->app->app_id,'key'=>$key,'value'=>serialize($value));
        $row   = app::get('base')->model('setting')->getRow(1,$filter);
        if($row)
        {
            $return = app::get('base')->model('setting')->update($data,$filter);
        }
        else
        {
            $return = app::get('base')->model('setting')->insert($data);
        }
        $this->__app_conf[$key]=$value;
        return (bool)$return;
    }

}
