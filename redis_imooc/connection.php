<?php
/*************************************************************************
 Author: chunguang.hu(huchunguang123@gmail.com)
 Created Time: 五  3/10 17:49:25 2017
 File Name: connection.php
 Description: 
 ************************************************************************/
echo '<xmp>';
$redis = new Redis();
// print_r($redis);
$con_res=$redis->connect('localhost','6379');
if($con_res === false)
{
    echo '连接redis服务失败';
}
$redis->auth('root');
//设置vvalue的前缀名称
$redis->setOption(Redis::OPT_PREFIX,'myAppName::');
$redis_dbSize=$redis->dbSize();
$redis_info=$redis->info();
$redis->save();
$redis_lastSave=$redis->lastSave();
$server_time=date('Y-m-d H:i:s',current($redis->time()));
$redis->set('phptest1','this is a phptest1',array('nx','ex'=>100));
$redis->setex('phptest2',3600,'this is a phptest2');
$redis->del('phptest2');
echo $redis->get('phptest2');
var_dump($redis->exists('phptest1'));
print_r($redis->mget(array('phptest1','phptest2')));
echo$random_key = $redis->randomKey();
