<?php
require '../helper.php';
require baseUrl('model/Response.php');

$response = new Response();
$from = $response->getIp();
$port = $response->getPort();
$socket = $response->getSocket();

define('GET_FILES_LIST', '1');
define('SEND_FILE', '2');
define('DOWNLOAD_FILE', '3');


$receivedFilesDir = 'received_files';

// Check if the directory exists, if not, create it
if (!is_dir($receivedFilesDir)) {
    mkdir($receivedFilesDir, 0777, true);
}

echo "Waiting for data... \n";
while (true) {
    // Receive data from the client 
    $receive_socket = socket_recvfrom($socket, $requestType, 4096, 0, $from, $port);

    if ($receive_socket) {
        // list($requestType, $filename, $littleChunk) = explode(':', $buf, 3);

        switch ($requestType) {
            case '1':
                // Handle request to get the list of files
                $response->getFilesList();
                break;

            case '3':
               $response->sendFile();
                break;

            case '2':
                // Handle file transfer
                list($filename, $fileChunk) = explode(':', $data, 2);
                $filePath = "{$receivedFilesDir}/{$filename}";
                $file = fopen($filePath, 'ab');

                if ($file) {
                    fwrite($file, $fileChunk);
                    fclose($file);
                    $bytesReceived = filesize($filePath);

                    // Display progress information
                    echo "\rReceiving file: " . number_format($bytesReceived) . " bytes received";
                    
                    // Check if the transfer is complete
                    if ($bytesReceived == filesize($filePath)) {
                        echo "\nFile transfer complete. File size: " . number_format(filesize($filePath)) . " bytes\n";
                        clearstatcache();
                        echo "\nSaved to {$filePath}\n";
                    }
                } else {
                    echo "Error opening file for writing\n";
                }
                break;

            default:
                echo "Unknown request type: {$requestType}\n";
        }
    } else {
        echo "Could not receive data \n";
    }
}
