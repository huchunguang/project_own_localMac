<?php
header('content-type:text/html;charset=utf-8');
echo '<xmp>';
$redis=new Redis();
$redis->connect('localhost','6379');
$redis->auth('root');
$redis->delete('x','y');
$redis->lPush('x','abc');
$redis->lPush('x','def');
$redis->lPush('y','123');
$redis->lPush('y','456');
$redis->rpoplpush('x','y');
print_r($redis->lrange('x',0,-1));
print_r($redis->lrange('y',0,-1));
echo $redis->lSize('x');
$redis->set('isNotList','string1');
var_dump($redis->lSize('isNotList'));
