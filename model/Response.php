<?php 

class Response {
    protected $socket;
    protected $port = 8088;
    protected $ip = '127.0.0.1';
    protected $buf;
    protected $MAX_SIZE = 1024;
    private $fileId;
    private $filename;
    private $fileContent;
    private $responseType;
    private $responseMessage;

    public function __construct() {
        $this->socket = socket_create(AF_INET, SOCK_DGRAM, 0);
        if (!$this->socket) {
            die("Could not create socket");
        }
        
        echo "Socket connected \n";
        $socket_bind = socket_bind($this->socket, $this->ip, $this->port);

        if ($socket_bind == false) {
            die("Could not bind socket \n");
        }

        echo "Socket binded \n";

    }
    public function getSocket() {
        return $this->socket;
    }
    public function getIp() {
        return $this->ip;
    }
    public function getPort() {
        return $this->port;
    }
}
