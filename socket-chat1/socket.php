<?php
// Creating a socket
if(!($sock = socket_create(AF_INET, SOCK_STREAM, 0)))
{	// Error handling
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);    
    die("Couldn't create socket: [$errorcode] $errormsg <br/>");
}
echo "Socket created <br/>";

// Connect to a Server
if(!socket_connect($sock , '127.0.0.1' , 80))
{   $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);     
    die("Could not connect: [$errorcode] $errormsg <br/>");
} 
echo "Connection established <br/>";

//Send the message to the server
$message = "Hello";
if( ! socket_send ( $sock , $message , strlen($message) , 0))
{   $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
    die("Could not send data: [$errorcode] $errormsg <br/>");
} 
echo "Message send successfully <br/>";

//Now receive reply from server
if(socket_recv ($sock , $buf , 2045 , MSG_WAITALL ) === FALSE)
{   $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);     
    die("Could not receive data: [$errorcode] $errormsg <br/>");
} 
echo $buf."<br/>";

// Close socket
socket_close($sock);





?>