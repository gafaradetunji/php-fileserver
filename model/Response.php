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

    // private function filesList($receivedFilesDir) {
    //     // $filesList = scandir($this->received_file);
    //     // $fileInfo = [];

    //     // foreach ($filesList as $filename) {
    //     //     if ($filename != "." && $filename != "..") {
    //     //         $filePath = $this->received_file . '/' . $filename;
    //     //         $fileInfo[] = [
    //     //             'filename' => $filename,
    //     //             'size' => filesize($filePath),
    //     //         ];
    //     //     }
    //     // }

    //     // $filesInfoStr = serialize($fileInfo);
    //     // socket_sendto($this->socket, $filesInfoStr, strlen($filesInfoStr), 0, $this->ip, $this->port);
    //     $filesList = scandir($receivedFilesDir);
    //             $fileInfo = [];

    //             foreach ($filesList as $filename) {
    //                 if ($filename != "." && $filename != "..") {
    //                     $filePath = $receivedFilesDir . '/' . $filename;
    //                     $fileInfo[] = [
    //                         'filename' => $filename,
    //                         'size' => filesize($filePath),
    //                     ];
    //                 }
    //             }

    //             $filesInfoStr = serialize($fileInfo);
    //             socket_sendto($this->socket, $filesInfoStr, strlen($filesInfoStr), 0, $this->ip, $this->port);
    // }

    // public function getFilesList($receivedFilesDir) {
    //     $this->filesList($receivedFilesDir);
    // }

    public function sendFile() {
        $receive_download_file = socket_recvfrom($this->socket, $data, 4096, 0, $this->ip, $this->port);
        
        if($receive_download_file) {
            $requestedFile = trim($data);
            // var_dump($requestedFile);
            $filePath = 'public/' .$this->received_file . '/' .$requestedFile;
            $filePath = baseUrl($filePath);
            $chunkSize = 1024;
            var_dump($filePath);

            if (file_exists($filePath)) {
                // Read the file content
                $file = fopen($filePath, 'rb');

                $bytesSent = 0;
                $fileSize = filesize($filePath);

                while (!feof($file)) {
                    $chunk = fread($file, $chunkSize);
                    $encodedChunk = base64_encode($chunk);

                    $sent = socket_sendto($this->socket, $encodedChunk, strlen($encodedChunk), 0, $this->ip, $this->port);
                    if (!$sent) {
                        echo "Error sending chunk: \n";
                        break;
                    }

                    $bytesSent += $chunkSize;
                    echo "Sent $bytesSent of $fileSize bytes\n";
                }

                fclose($file);

                if ($bytesSent === $fileSize) {
                    echo "File sent successfully\n";
                }
            } 
            else {
                $errorMessage = "File not found: {$requestedFile}";
                socket_sendto($this->socket, $errorMessage, strlen($errorMessage), 0, $this->ip, $this->port);
            }
        }
    }
}
