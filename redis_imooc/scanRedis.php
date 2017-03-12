<?php
header('content-type:text/html;charset=utf-8');
$redis=new Redis();
$redis->connect('localhost','6379');
$redis->auth('root');
$it = NULL; /* Initialize our iterator to NULL */
$redis->setOption(Redis::OPT_SCAN, Redis::SCAN_RETRY); /* retry when we get no keys back */
while($arr_keys = $redis->scan($it)) {
    foreach($arr_keys as $str_key) {
        echo "Here is a key: $str_key".'<br />';
    }
    echo "No more keys to scan!\n";
}
