<?php
header('content-type:text/html;charset=utf-8');
$redis = new Redis();
$redis->connect('localhost','6379');
$redis->auth('root');
if(!function_exists('f'))
{
    function f($redis_ins,$chan,$msg)
    {
        switch ($chan)
        {
            case 'channel-1':
                echo 'receive from the channel-1 publisher message has successfully';
                break;
            default:
                ;
                break;
        }
    }
}
echo 'fasfasdf';die;
$redis->subscribe(array('channel-1'),'f');
