<?php
header('content-type:text/html;charset=utf-8');
$redis=new Redis();
$redis->connect('localhost','6379');
$redis->auth('root');
$redis->hSet('city','上海','shanghai');
$redis->hSet('city','北京','beijing');
$redis->hSet('city','深圳','shenzhen');
$redis->hSet('city','广州','guangzhou');
echo $redis->hGet('city','上海').'<br />';
echo '<hr color="red"/>';
echo $redis->hLen('city');
$redis->setOption(Redis::OPT_SCAN,Redis::SCAN_RETRY);
$it=null;
while($all_keys=$redis->hScan('city',$it))
{
    foreach ($all_keys as $str_field=>$str_value)
    {
        echo $str_field.'=>'.$str_value.'<br />';
    }
}