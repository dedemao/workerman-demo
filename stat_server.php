<?php
use Workerman\Lib\Timer;
use Workerman\Worker;
require_once './Workerman/Autoloader.php';
define(HEARTBEAT_TIME,55);      //心跳间隔55秒
$task = new Worker('websocket://0.0.0.0:2001');
$task->count = 1;
$task->num=0;       //统计在线人数
$task->onWorkerStart = function () use ($task){
    //心跳检测
    Timer::add(1,function () use ($task){
        $now = time();
        foreach ($task->connections as $connection){
            if(!isset($connection->lastTime)){
                $connection->lastTime = time();
                continue;
            }
            if($now - $connection->lastTime > HEARTBEAT_TIME){
                $connection->close();
            }
        }
    });
};
$task->onMessage = function($connection, $buffer) use ($task)
{
    $connection->lastTime = time();
    foreach($task->connections as $connection)
    {
        $connection->send(count($task->connections));
    }
};
$task->onClose = function ($connection) use ($task){
    $connection->close();

    foreach($task->connections as $connection)
    {
        $connection->send(count($task->connections)-1);
    }
};

Worker::runAll();
