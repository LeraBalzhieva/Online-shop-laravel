<?php

namespace App\Service;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Сервис для работы с RabbitMQ.
 */
class RabbitmqService
{

    private AMQPStreamConnection $connection;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
    }

    /**
     * Отправляет сообщение в очередь.
     * @param array $data
     * @param string $queueName
     * @return void
     */
    public function produce(array $data, string $queueName)
    {
        $channel = $this->connection->channel();

        $channel->queue_declare($queueName, false, false, false, false);

        $data = json_encode($data);
        $msg = new AMQPMessage($data);
        $channel->basic_publish($msg, '', $queueName);
    }

    /**
     * Подписывается на очередь и обрабатывает сообщения через callback.
     * @param string $queueName
     * @param callable $callback
     * @return void
     */
    public function consume(string $queueName, callable $callback)
    {
        echo 'test';
        $channel = $this->connection->channel();

        $channel->queue_declare($queueName, false, false, false, false);

        $channel->basic_consume($queueName, '', false, true, false, false, $callback);

        try {
            $channel->consume(true);
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }
    }
}
