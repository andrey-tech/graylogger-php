<?php

namespace Test\Functional;

use RuntimeException;

class SocketServerStub
{
    private $host = 'localhost';
    private $port = 12201;

    private $socket;
    private $client;

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function setPort($port)
    {
        $this->port = $port;
    }

    public function createSocket()
    {
        if (isset($this->socket)) {
            return;
        }

        $socket = socket_create(AF_INET, SOCK_STREAM, 0);
        if ($socket === false) {
            throw new RuntimeException("SocketServerStub: Can't socket_create(): " . $this->getSocketLastError());
        }

        if (!socket_bind($socket, $this->host, $this->port)) {
            throw new RuntimeException(
                "SocketServerStub: Can't socket_bind() to {$this->host}:{$this->port}: " . $this->getSocketLastError()
            );
        }

        if (!socket_listen($socket)) {
            throw new RuntimeException("SocketServerStub: Can't socket_listen(): "  . $this->getSocketLastError());
        }

        if (!socket_set_nonblock($socket)) {
            throw new RuntimeException("SocketServerStub: Can't socket_set_nonblock(): " . $this->getSocketLastError());
        }

        $this->socket = $socket;
    }

    public function accept()
    {
        if (!isset($this->socket)) {
            throw new RuntimeException(
                "SocketServerStub: Create socket first: createSocket(): " . $this->getSocketLastError()
            );
        }

        // Минимальная задержка для того, чтобы клиент точно успел подключиться к серверу
        usleep(1E4);

        if (!isset($this->client)) {
            $this->client = socket_accept($this->socket);
            if ($this->client === false) {
                throw new RuntimeException("SocketServerStub: Can't socket_accept(): " . $this->getSocketLastError());
            }
        }

        $content = socket_read($this->client, 1024);
        if ($content === false) {
            throw new RuntimeException("SocketServerStub: Can't socket_read(): "  . $this->getSocketLastError());
        }
        return $content;
    }

    private function getSocketLastError()
    {
        return socket_strerror(socket_last_error());
    }

    public function __destruct()
    {
        socket_close($this->socket);
        unset($this->socket);
    }
}
