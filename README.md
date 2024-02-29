# PHP UDP File Server

## Overview

This project implements a simple file server and client using PHP. The server allows clients to list available files, download files from the server, and upload files to the server via a UDP connection.

## Features

1. **List Files**: Clients can request a list of available files on the server.
2. **Download Files**: Clients can download files from the server.
3. **Upload Files**: Clients can upload files to the server.

## Requirements

- PHP (with UDP support)
- A compatible web server (e.g., Apache, Nginx)
- Basic knowledge of PHP and networking

## Setup

1. Clone this repository to your local machine.
2. Configure your web server to serve PHP files from the project directory.
3. Start your web server and ensure it supports UDP connections.

## Usage

### Server

1. Run the server script (`server.php`):
   ```
   php server.php
   ```
2. The server will listen for UDP requests on a specific port (in this case, 8088).

### Client

1. Run the client script (`client.php`):
   ```
   php client.php
   ```
2. Use the following commands:
   - `1`: List available files on the server.
   - you first click type 2 or the number to make the file ready for a particular response then the `<local_file> <remote_filename>`to upload a file to the server.
   - you first click type 3 or the number to make the file ready for a particular response then the `<filename>`to Download a file from the server.

### Example Commands

- To list available files:

  ```
  1
  ```

- To download a file:

  ```
  3
  ```

- To upload a file:
  ```
  2
  ```

## Notes

- UDP is connectionless, so ensure reliable file transfer mechanisms are implemented (e.g., checksums).
- This project serves as a basic example; so there are no securities and error handling for production use.

---
