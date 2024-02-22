<?php
require './helper.php';
require baseUrl('model/Response.php');

$sock = socket_create(AF_INET, SOCK_DGRAM, 0);

if (!$sock) {
    die("Couldn't create socket");
}

echo "Socket created \n";

$serverAddress = '127.0.0.1';
$serverPort = 8088;

define('GET_FILES_LIST', '1');
define('SEND_FILE', '2');
define('DOWNLOAD_FILE', '3');

echo "Choose an option:\n";
echo GET_FILES_LIST . ": Get the list of files on the server\n";
echo SEND_FILE . ": Send a file to the server\n";
echo DOWNLOAD_FILE . ": Download a file from the server\n";

$option = trim(fgets(STDIN));
$request = (string) $option;
socket_sendto($sock, $request, strlen($request), 0, $serverAddress, $serverPort);

switch ($option) {
    case '1':
        // Request the list of files from the server
        $request = '1';
        echo "Sending request: $request\n";
        socket_sendto($sock, $request, strlen($request), 0, $serverAddress, $serverPort);

        // Receive the list of files from the server
        $response = '';
        socket_recvfrom($sock, $response, 4096, 0, $serverAddress, $serverPort);

        $filesInfo = unserialize($response);

        if ($filesInfo) {
            echo "List of files on the server:\n";
            foreach ($filesInfo as $file) {
                echo "Filename: {$file['filename']}, Size: " . number_format($file['size']) . " bytes\n";
            }
        } else {
            echo "Error receiving files list from the server\n";
        }

        break;

    case '2':
        // Enter the path of the file to send
        echo 'Enter the path of the file to send (or type "exit" to quit): ';
        $filePath = trim(fgets(STDIN));

        if ($filePath === 'exit') {
            exit;
        }

        if (!is_file($filePath)) {
            die("File not found. Please enter a valid file path.\n");
        }

        $filename = pathinfo($filePath, PATHINFO_BASENAME);

        // Open the file in binary mode
        $file = fopen($filePath, 'rb');

        // Set the chunk size
        $chunkSize = 4096; // Adjust as needed
        $bytesSent = 0;
        $fileSize = filesize($filePath);

        while (!feof($file)) {
            // Read a chunk of data from the file
            $chunk = fread($file, $chunkSize);

            // Create the message with the current chunk
            if ($bytesSent >= $fileSize) {
                $littleChunk = min($bytesSent, $fileSize);
                $message = "file:{$filename}:{$littleChunk}";
            } else {
                $message = "file:{$filename}:{$chunk}";
            }

            // Send the message to the server
            $send = socket_sendto($sock, $message, strlen($message), 0, $serverAddress, $serverPort);

            if (!$send) {
                die('Could not send file data');
            }

            // Track the file sending information
            $bytesSent += $chunkSize;

            // Calculate the progress percentage
            echo "Sending {$filePath} is " . min($bytesSent, $fileSize) . " bytes out of " . $fileSize . "\n";
            if (feof($file)) {
                echo "File transfer complete. File size: " . number_format($fileSize) . " bytes\n";
            }

            usleep(100000);
        }

        fclose($file);
        break;

    case '3':
        // Enter the filename to download
        echo 'Enter the filename to download (or type "exit" to quit): ';
        $filenameToDownload = trim(fgets(STDIN));

        if ($filenameToDownload === 'exit') {
            exit;
        }

        // Send a download request to the server
        $downloadRequest = "{$filenameToDownload}";
        socket_sendto($sock, $downloadRequest, strlen($downloadRequest), 0, $serverAddress, $serverPort);

        // Receive the file content from the server
        $fileContent = '';
        $chunk = '';
        do {
            socket_recvfrom($sock, $chunk, 1024, 0, $serverAddress, $serverPort);
            $fileContent .= $chunk;
        } while (!empty($chunk)); // Continue receiving until all chunks are received

        // Decode the entire received content
        $decodedContent = base64_decode($fileContent);

        // Save the decoded content to the file
        $downloadedFilePath = "downloaded_{$filenameToDownload}";
        file_put_contents($downloadedFilePath, $decodedContent);

        echo "File downloaded and saved to: {$downloadedFilePath}\n";

        break;

    default:
        echo "Invalid option\n";
}

socket_close($sock);
