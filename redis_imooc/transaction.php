<?php
header('content-type:text/html;charset=utf-8');
echo '<pre>';
$redis = new Redis();
$redis->connect('localhost','6379');
$redis->auth('root');
$multi_res=$redis->multi()->set('multiSet1','multiSet1')->get('multiSet1')->set('multiSet2','multiSet2')->get('multiSet2')->exec();
print_r($multi_res);
#parameters NONE
var_dump($redis->isConnected());
var_dump($redis->getHost());
var_dump($redis->getAuth());
var_dump($redis->getPort());
var_dump($redis->getTimeout());
var_dump($redis->getDBNum());
var_dump($redis->getReadTimeout());
var_dump($redis->getPersistentID());
#parameters NONE
die;