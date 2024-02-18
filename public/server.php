<?php
require '../helper.php';
require baseUrl('model/Response.php');

$response = new Response();
$from = $response->getIp();
$port = $response->getPort();
$socket = $response->getSocket();

$receivedFilesDir = 'received_files';

// Check if the directory exists, if not, create it
if (!is_dir($receivedFilesDir)) {
    mkdir($receivedFilesDir, 0777, true);
}

$bytesReceived = 0;

echo "Waiting for data... \n";
while (true) {

    // Receive data from the client 
    $receive_socket = socket_recvfrom($socket, $buf, 4096, 0, $from, $port);
    
    if ($receive_socket) {
        list($dataType, $filename, $fileChunk) = explode(':', $buf, 3);

        switch ($dataType) {
            case 'file':
                // echo "Received file data from {$from}:{$port}\n";

                $filePath = "{$receivedFilesDir}/{$filename}";
                $file = fopen($filePath, 'ab'); 

                if ($file) {
                    fwrite($file, $fileChunk);
                    fclose($file);
                    $bytesReceived += strlen($fileChunk);

                    // Display progress information
                    echo "\rReceiving file: " . number_format($bytesReceived) . " bytes received";
                    // Check if the transfer is complete
                    if (filesize($filePath)) {
                        echo "\nFile transfer complete. File size: " . number_format(filesize($filePath)) . " bytes\n";
                        clearstatcache();
                        echo "\nSaved to {$filePath}\n";
                    }
                    // echo "FileSize is: " . filesize($filePath) . " bytes\n";

                } else {
                    echo "Error opening file for writing\n";
                }
                
                break;
                default:
                echo "Unknown data type: {$dataType}\n";
        }
    } else {
        echo "Could not receive data \n";
    }
}
