<?php
require './helper.php';
require baseUrl('model/Response.php');

// $response = new Response();

$sock = socket_create(AF_INET, SOCK_DGRAM, 0);

if (!$sock) {
    die("Couldn't create socket");
}

echo "Socket created \n";

$serverAddress = '127.0.0.1';
$serverPort = 8088;

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
$fileSize = strlen($filePath);

while (!feof($file)) {
    // Read a chunk of data from the file
    
    $chunk = fread($file, $chunkSize);

    // Create the message with the current chunk
    // check if the chunk is greater than the file size
    if($bytesSent >= filesize($filePath)) {
        $littleChunk = min($bytesSent , filesize($filePath));
        $message = "file:{$filename}:{$littleChunk}";
    }
    else {
        $message = "file:{$filename}:{$chunk}";
    }

    // Sends the message to the server
    $send = socket_sendto($sock, $message, strlen($message), 0, $serverAddress, $serverPort);

    if (!$send) {
        die('Could not send file data');
    }

    // Tracks the file sending information
    $bytesSent += $chunkSize;

    // Calculate the progress percentage
    echo "Sending {$filePath} is " . min($bytesSent, filesize($filePath)) . " bytes out of " . filesize($filePath) . "\n";
    if(feof($file)){
        echo "File transfer complete. File size: " . number_format(filesize($filePath)) . " bytes\n";
    }
    
    usleep(100000);
}

fclose($file);

socket_close($sock);

echo "File sent successfully.\n";
