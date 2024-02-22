<?php 
// require '../helper.php';

class Response {
    protected $socket;
    protected $port = 8088;
    protected $ip = '127.0.0.1';
    protected $buf;
    protected $MAX_SIZE = 1024;
    protected $received_file = 'received_files';
    private $fileId;
    private $filename;
    private $fileContent = [];
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

    private function filesList() {
        $filesList = scandir($this->received_file);
        $fileInfo = [];

        foreach ($filesList as $filename) {
            if ($filename != "." && $filename != "..") {
                $filePath = $this->received_file . '/' . $filename;
                $fileInfo[] = [
                    'filename' => $filename,
                    'size' => filesize($filePath),
                ];
            }
        }

        $filesInfoStr = serialize($fileInfo);
        socket_sendto($this->socket, $filesInfoStr, strlen($filesInfoStr), 0, $this->ip, $this->port);
    }

    public function getFilesList() {
        $this->filesList();
    }

    public function sendFile() {
        $receive_download_file = socket_recvfrom($this->socket, $data, 4096, 0, $from, $port);
        if($receive_download_file) {
            $requestedFile = trim($data);
            $filePath = __DIR__ . '/' . $this->received_file . '/' .$requestedFile;

            if (file_exists($filePath)) {
                // Read the file content
                $fileContent = file_get_contents($filePath);
                $fileContentStr = base64_encode($fileContent);

                $download = socket_sendto($this->socket, $fileContentStr, strlen($fileContentStr), 0, $from, $port);
                if(!$download) {
                    echo "Could not sent file to client \n";
                    exit;
                }

                echo "File sent to client \n";

            } else {
                $errorMessage = "File not found: {$requestedFile}";
                socket_sendto($this->socket, $errorMessage, strlen($errorMessage), 0, $from, $port);
            }
        }
    }
}
