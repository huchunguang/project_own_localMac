<?php
require_once __DIR__.'/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
$connection=new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel=$connection->channel();
$channel->queue_declare('task_queue',false,true,false,false);
$callback=function($msg){
    echo "Received ".$msg->body."\n";
    sleep(substr_count($msg->body, '.'));
    echo "[x] done! \n";
};
$channel->basic_qos(null, 1, null);
$channel->basic_consume('task_queue','',false,false,false,false,$callback);
while (count($channel->callbacks))
{
    $channel->wait();
}