<?php
header('content-type:text/html;charset=utf-8');
$redis=new Redis();
$redis->connect('localhost','6379');
$redis->auth('root');
$redis->sAdd('s',5);
$redis->sAdd('s',1);
$redis->sAdd('s',2);
$redis->sAdd('s',4);
$redis->sAdd('s',3);
var_dump($redis->sort('s', array(
    'sort' => 'desc',
    'limit' => array(
        0,
        2
    ),
    'alpha' => true
)));
