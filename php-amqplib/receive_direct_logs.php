<?php
require_once __DIR__.'/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
$connection=new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel=$connection->channel();
$channel->exchange_declare('direct_exchange', 'direct',false,false,false);
list($queue_name,,)=$channel->queue_declare("",false,false,true,false);
$severities=array_slice($argv, 1);
if (empty($severities))
{
    file_put_contents('php://stderr', "Userage:$argv[0] [info] [warnings] [error]\n");
    exit(1);
}
foreach ($severities as $severity)
{
    $channel->queue_bind($queue_name, 'direct_exchange',$severity);
}
echo "[*] Waiting for logs , To exit press CTRL+C \n\r";
$callback=function($msg){
    echo "[X]".$msg->delivery_info['routing_key'].":".$msg->body."\n\r";
};
$channel->basic_consume($queue_name,"",false,true,false,false,$callback);
while (count($channel->callbacks))
{
    $channel->wait();
}
$channel->close();
$connection->close();
