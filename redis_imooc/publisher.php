<?php
header('content-type:text/html;charset=utf-8');
$redis = new Redis();
$redis->connect('localhost','6379');
$redis->auth('root');
# warning this function will problely change in the further 
$pub_res=$redis->publish('channel-1','this is a channel-1 message');//send  a messge
var_dump($pub_res);die;
