<?php
// Request dari Client ke Server
$request = xmlrpc_encode_request("method", array("nim" => "220605110110", "nama" => "Ali"));
$context = stream_context_create(array(
    'http' => array(
        'method' => "POST",
        'header' => "Content-Type: text/xml;charset=UTF-8",
        'content' => $request
    )
));

// Ambil data dari Server
$file = file_get_contents("http://192.168.56.2/rpc-xml-simple/server.php?user=pengguna&password=pin", false, $context);

// Response dari Server ke Client
$response = xmlrpc_decode($file);
if ($response && xmlrpc_is_fault($response)) {
    trigger_error("xmlrpc: " . $response['faultString'] . " (" . $response['faultCode'] . ")");
} else {
    echo "<pre>";
    print_r($response);
    echo "</pre>";
    echo "--";
    echo "<br/>nim: " . $response[0]['nim'];
    echo "<br/>nama: " . $response[0]['nama'];
}
