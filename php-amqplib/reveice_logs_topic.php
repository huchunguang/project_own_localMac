<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
$connection=new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel=$connection->channel();
$channel->exchange_declare('topic_logs', 'topic' ,false,false,false);
list($queue_name,)=$channel->queue_declare("",false,false,true,false);
$bindkeys=array_slice($argv, 1);
if (empty($bindkeys)) 
{
    file_put_contents("php://stderr", "Userage: $argv[1] [bindKeys]\n\r");
    exit(1);
}
foreach ($bindkeys as $bindkey)
{
    $channel->queue_bind($queue_name, 'topic_logs',$bindkey);
}
echo "[*] Waiting for Logs To Exit Press CTRL+C\r\n";
$callback=function($msg){
    echo "Receive Logs".$msg->delivery_info['routing_key'].":".$msg->body."\r\n";
};
$channel->basic_consume($queue_name,'',false,true,false,false,$callback);
while (count($channel->callbacks)){
    $channel->wait();
}
$channel->close();
$connection->close();