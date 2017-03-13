<?php
header('content-type:text/html;charset=utf-8');
class base_queue
{
    private $queue;
    public function __construct()
    {
        if(!defined('MESSAGE_QUEUE') && !constant('MESSAGE_QUEUE'))
        {
            define('MESSAGE_QUEUE','base_queue_mysql');
        }
        return $this->set_queue(kernel::single(MESSAGE_QUEUE));
    }
    public function publish($message)
    {
        return $this->queue->publish($message);
    }
    public function consume()
    {
        return $this->queue->consume();
    }
    public function set_queue(bass_interface_queue $queue)
    {
        $this->queue=$queue;
        return $this->queue;
    }
}
