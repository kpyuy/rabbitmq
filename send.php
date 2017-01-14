<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use mPDF;

//print_r($_SERVER['argv']);
$arg = $_SERVER['argv'];
$cstName = $arg[1];
$csttel = $arg[2];
echo $cstName;
echo $csttel;
function createPdf(){
    $mpdf = new mPDF();
    //$mpdf->writeHTMLHeaders();
    $mpdf->WriteHTML('this is a test');
    $mpdf->Output();
}

function sendMsg(){
    //10.5.23.221    192.168.1.7
    $connection = new AMQPStreamConnection('192.168.1.7', 5672, 'admin', '123456');
    $channel = $connection->channel();

    $queueName = 'rpt_01';
    $channel->queue_declare($queueName, false, false, false, false);


    //$msg = new AMQPMessage('Hello World!');
    //$channel->basic_publish($msg, '', $queueName);

    $filename = "test.pdf";
    $handle = fopen($filename, "rb");//读取二进制文件时，需要将第二个参数设置成'rb'，读到的是二进制数据的数组
    $contents = fread($handle, filesize ($filename)); //通过filesize获得文件大小，将整个文件一下子读到一个字符串中
    fclose($handle);
    $msg = new AMQPMessage();
    $msg->setBody($contents);
    $channel->basic_publish($msg, '', $queueName);

    echo " [x] Sent 'Hello World!'\n";

    $channel->close();
    $connection->close();
}
//createPdf();
//sendMsg();

?>