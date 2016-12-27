<?php
require_once __DIR__.'/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
$connection=new AMQPStreamConnection('mq01.beta1.fn', 80, 'drp@rabbitmq', 'drp@rabbitmq','/');
$channel=$connection->channel();
// $channel->exchange_declare('commsoa.commspapi.updateSkuInfo', 'fanout',false,false,false);
// list($queue_name,,)=$channel->queue_declare("",false,false,true,false);
// $channel->queue_bind($queue_name, 'commsoa.commspapi.updateSkuInfo');
echo "[*] Waiting for logs, To exit CRTL+C \n\r";
$callback=function($msg){
    echo "[X]".$msg->body."\r\n";
};
$channel->basic_consume('drp.drp.changeSkuInfo','',false,true,false,false,$callback);
while (count($channel->callbacks)){
    $channel->wait();
}
$channel->close();
$connection->close();
