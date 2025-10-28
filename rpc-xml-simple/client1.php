<?
$file = file_get_contents("http://192.168.56.2/rpc-xml-simple/server.php?user=pengguna&password=pin", false, null);

$respons = xmlrpc_decode($file);
if ($respons && xmlrpc_is_fault($respons)) {
    trigger_error("xmlrpc: $respons[faultString] ($respons[faultCode])");
} else {
    echo "<pre>";
    print_r($respons);
    echo "</pre>";
    echo "----------------------------------------------------------------------";
    echo "<br/>nim :" . $respons['nim'];
    echo "<br/>nama :" . $respons['nama'];
    echo "<br/>kota :" . $respons['kota'];
}
