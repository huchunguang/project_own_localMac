<?php
header('content-type:text/html;charset=utf-8');
echo '<xmp>';
$redis=new Redis();
$redis->connect('localhost','6379');
$redis->auth('root');
$redis->sAdd('s0',1);
$redis->sAdd('s0',2);
$redis->sAdd('s0',3);
$redis->sAdd('s0',4);
$redis->sAdd('s1',1);
$redis->sAdd('s1',3);
$res=$redis->sDiff('s0','s1');
print_r($res);
$sStoreRes=$redis->sDiffStore('sStoreRes','s0','s1');
print_r($redis->sGetMembers('sStoreRes'));
var_dump($redis->sismember('s0',1));
var_dump($redis->sContains('s0',90));
$redis->sMove('s0','s1',2);
print_r($redis->sMembers('s1'));
echo $redis->sPop('s1');
print_r($redis->sMembers('s1'));
print_r($redis->sRandMember('s1',3));

