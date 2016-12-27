<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/config.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
class rabbit
{
    public $queue='';
    
    public $exchange_name='';
    public $exchange_type='';
    public $consumer_tag='';
    public $queue_name='';
    protected $_is_durable=true;//是否为persistent
    protected  $ch=null;//channel对象
    protected  $conn=null;//channel对象
    protected static $_instance=null;///AMQP对象
    
    private function __construct($exchange_name,$exchange_type)
    {
        $this->conn=new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
        $this->ch=$this->conn->channel();
        if (isset($exchange_name) && !empty($exchange_name))
        {
            $this->exchange_name=$exchange_name;
        }
        $this->exchange_type=$exchange_type;
    }
    public static function getInstance($exchange_name='',$exchange_type='fanout') 
    {
        if (is_null(self::$_instance))
        {
            self::$_instance=new rabbit($exchange_name,$exchange_type);
        }
        return self::$_instance;
    }
    public function sendList(array $data=[],$routing_key='',$delivery_persist=false)
    {
        $message_proper=[];
//         $this->ch->exchange_declare($this->exchange_name, $this->exchange_type,false,false,false);
        $route_key=isset($routing_key) && !empty($routing_key)? $routing_key:'';
        $data=json_encode($data);
        if (empty($data)) $data='Hello World!';
        if ($delivery_persist)
        {
            $message_proper=array(
                    'delivery'=>2,#make message persistent
            );
        }
        $msg=new AMQPMessage($data,$message_proper);
        $this->ch->basic_publish($msg,$this->exchange_name,$routing_key);
    }
    public function get($severities='',$is_durable=true,$autodel=false)
    {
        $this->_is_durable=$is_durable;
//         $this->ch->exchange_declare($this->exchange_name, $this->exchange_type,false,$is_durable,$autodel);
        list($queue_name,)=$this->ch->queue_declare("",false,$this->_is_durable,true,false);
//         $this->queue_name=$queue_name;
        if ($this->exchange_type!='fanout' && isset($severities) && !empty($severities))
        {
            $severities=explode('_', $severities);
            if (empty($severities))
            {
                file_put_contents('php://stderr', "Userage:$argv[0] [info] [warnings] [error]\n");
                exit(1);
            }
            foreach ($severities as $severity)
            {
                $this->ch->queue_bind($queue_name, $this->exchange_name,$severity);
            }    
        }
        else
        {
//             $this->ch->queue_bind($queue_name, $this->exchange_name);
        }
        return $this->prepare_consumer();
    }
    
    public function prepare_consumer($noLocal = false, $noAck = true, $exclusive = false, $noWait = false)
    {
        $this->ch->basic_consume($this->exchange_name, '', $noLocal, $noAck, $exclusive, $noWait, 
                array($this,'process_message'));
        while (count($this->ch->callbacks)) {
            $this->ch->wait();
        }
    }
    /**
     * @param \PhpAmqpLib\Message\AMQPMessage $msg
     */
    function process_message($msg)
    {
        echo "\n--------\n";
        echo $msg->body;
        echo "\n--------\n";
    
//         $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    
        // Send a message with the string "quit" to cancel the consumer.
        if ($msg->body === 'quit') {
            $msg->delivery_info['channel']->basic_cancel($msg->delivery_info['consumer_tag']);
        }
        return $msg->body;
    }
    public function __destruct()
    {
        $this->conn->close();
        $this->ch->close();
    }
}
// $result=rabbit::getInstance('drp.drp.changeSkuInfo','fanout')->get();
// $result=json_decode($result,true);
// print_r($result);die;
 $sender=rabbit::getInstance('drp.drp.qty.updateScale')->sendList(['username'=>'chunguang.hu','msg'=>'this is a test message from chunguang.hu']);
////////////////////////////////////////////////////////////////////////
