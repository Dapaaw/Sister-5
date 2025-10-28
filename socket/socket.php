<?php
if (!($sock = socket_create(AF_INET, SOCK_STREAM, 0))) {
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
    die("Could't create socket:[$errorcode] $errormsg\n");
}
echo "Socket created \n------------------\n";

$address = gethostbyname("www.google.com");

if (!socket_connect($sock, $address, 80)) {
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
    die("Could't connect : [$errorcode] $errormsg");
}

echo "Connection established \n------------------\n";

$message = "GET / HTTP/1.1\r\n\r\n";
if (!socket_send($sock, $message, strlen($message), 0)) {
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
    die("Could't send data [$errorcode] $errormsg\n");
}

echo "Messsage send successfully \n--------------------\n";

if (socket_recv($sock, $buf, 2045, MSG_WAITALL) === FALSE) {
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
    die("Could't receive data [$errorcode] $errormsg\n");
}
echo $buf . "\n-------------------\n";

socket_close($sock);
