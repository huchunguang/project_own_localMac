<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
$connection=new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel=$connection->channel();
$channel->exchange_declare('direct_exchange', 'direct',false,false,false);
$data=implode(" ", array_slice($argv, 2));
if (empty($data))$data='Hello World';
$severity=isset($argv[1]) && !empty($argv[1])?$argv[1]:'info';
$msg=new AMQPMessage($data);
$channel->basic_publish($msg,'direct_exchange',$severity);
echo "[X] Sent {$data} \r\n";
$channel->close();
$connection->close();