<?php
header('content-type:text/html;charset=utf-8');
echo '<xmp>';
$redis=new Redis();
$redis->connect('localhost','6379');
$redis->auth('root');
$redis->set('x','this is a name as X expire key');
$redis->setRange('x',2,'huchunguang');
$redis->getRange('x',2,12);
echo $redis->strlen('x');
die;
echo $redis->get('x');
$redis->expire('x',3);

sleep(5);
$get_res_x=$redis->get('x');
var_dump($get_res_x);
$all_keys= $redis->keys('*');
print_r($all_keys);