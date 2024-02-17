<?php

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

$fileContent = file_get_contents($filePath);
$fileSize = strlen($fileContent);

$filename = pathinfo($filePath, PATHINFO_BASENAME);

$message = "file:{$filename}:";

$chunkSize = 512;
$chunks = str_split($fileContent, $chunkSize);

foreach ($chunks as $chunk) {
    $message .= $chunk;
    $send = socket_sendto($sock, $message, strlen($message), 0, $serverAddress, $serverPort);

    if (!$send) {
        die('Could not send file data');
    }
}

socket_close($sock);

echo "File sent successfully.\n";
