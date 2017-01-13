<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('10.5.23.221', 5672, 'admin', '123456');
$channel = $connection->channel();

$queueName = 'rpt_01';
$channel->queue_declare($queueName, false, false, false, false);

$msg = new AMQPMessage('Hello World!');
$channel->basic_publish($msg, '', $queueName);

echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();

?>