<?php

namespace App\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class PingAction implements ServerMiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('hello', false, false, false, false);

        $msg = new AMQPMessage('Hello World!');
        $channel->basic_publish($msg, '', 'hello');

        return new JsonResponse(['ack' => time()]);
    }
}
