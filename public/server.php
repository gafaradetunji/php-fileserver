<?php
require '../helper.php';
require baseUrl('model/Response.php');

$response = new Response();

$receivedFilesDir = 'received_files';

// Check if the directory exists, if not, create it
if (!is_dir($receivedFilesDir)) {
    mkdir($receivedFilesDir, 0777, true);
}

while (true) {
    echo "Waiting for data... \n";
    $from = $response->getIp();
    $port = $response->getPort();
    $socket = $response->getSocket();

    // Receive data from the client 
    $receive_socket = socket_recvfrom($socket, $buf, 1024, 0, $from, $port);

    if ($receive_socket) {
        list($dataType, $filename, $fileChunk) = explode(':', $buf, 3);

        switch ($dataType) {
            case 'file':
                echo "Received file data from {$from}:{$port}\n";

                $filePath = "{$receivedFilesDir}/{$filename}";
                $file = fopen($filePath, 'a'); 
                // $filesize = filesize($filepath);

                if ($file) {
                    fwrite($file, $fileChunk);
                    fclose($file);
                    echo "FileSize is: " . filesize($filePath) . " bytes\n";
                    // echo "Saved to {$filePath}\n";
                } else {
                    echo "Error opening file for writing\n";
                }
                
                echo "Saved to {$filePath}\n";
                // echo "FileSize is {${filesize($filepath)}} \n";
                break;
                default:
                echo "Unknown data type: {$dataType}\n";
        }
    } else {
        echo "Could not receive data \n";
    }
}

// socket_close($socket);  // Commented out as it's not used in your code

// echo "File data received successfully. \n";
