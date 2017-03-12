<?php
header('content-type:text/html;charset=utf-8');
// echo '<xmp>';
$redis=new Redis();
$redis->connect('localhost','6379');
$redis->auth('root');
$redis->delete('key1');
$redis->lInsert('key1',Redis::AFTER,'A','X');
$redis->lPush('key1','A');
$redis->lpush('key1','B');
$redis->lPush('key1','C');
$redis->lInsert('key1',Redis::BEFORE,'C','X');
$redis->lInsert('key1',Redis::AFTER,'X','K');
print_r($redis->lrange('key1',0,-1));
$redis->delete('key1');
$redis->lPush('key1','C');
$redis->lPush('key1','B');
$cur_mem_amount=$redis->lPush('key1','A');
$redis->lRemove('key1','A',0);
$redis->lSet('key1',0,'huchunguang');
echo $redis->lGet('key1',0);
echo '<hr color="purple"/>';
print_r($redis->lrange('key1',0,-1));

// $push_res=$redis->lPush('key2','this is a key2 value');
// var_dump($push_res);

