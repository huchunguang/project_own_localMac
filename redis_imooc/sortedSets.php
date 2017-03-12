<?php
header('content-type:text/html;charset=utf-8');
//echo '<xmp>';
$redis=new Redis();
$redis->connect('localhost','6379');
$redis->auth('root');
$redis->zAdd('zset1',1,'value1');
$redis->zAdd('zset1',5,'value5');
$redis->zAdd('zset1',0,'value0');
print_r($redis->zRange('zset1',0,-1));
echo $redis->zSize('zset1');
echo $redis->zCount('zset1',0,4);
$redis->delete('zset2');
$redis->zIncrBy('zset2',5,'member1');
$redis->zAdd('zset2',15,'member2');
$redis->zAdd('zset2',20,'member3');
$redis->zAdd('zset2',18,'member4');
print_r($redis->zRange('zset2',0,-1,true));
print_r($redis->zRangeByScore('zset2', 10, 20, array(
    'withscores' => true
)));
echo $redis->zRank('zset2','member3');
echo $redis->zRevRank('zset2','member3');
echo '<hr color="red"/>';
echo $redis->zScore('zset2','member4');
#zScan
$it=null;
$redis->setOption(Redis::OPT_SCAN,Redis::SCAN_RETRY);
while($all_keys=$redis->zscan('zset',$it,'*'))
{
    foreach ($all_keys as $z_field=>$z_value)
    {
        echo $z_field.'=>'.$z_value.'<br />';
    }
}
exit;
