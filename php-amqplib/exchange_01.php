<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
$connection=new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel=$connection->channel();
// $channel->queue_declare('task_exchange_queue',false,true,false,false);#Queue declare
$channel->exchange_declare('logs', 'fanout',false,false,false);
$data="This is a Exchange Test123\n";
$msg=new AMQPMessage($data,array('delivery_mode'=>2));
$channel->basic_publish($msg,'logs');
echo " [X] Sent {$data} \n\r";
$channel->close();
$connection->close();


