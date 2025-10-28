<?php
error_reporting(1);
class Client
{
    private $api;
    public function __construct($url)
    {
        $this->api = new SoapClient($url);
        unset($url);
    }

    public function tampil_semua_data()
    {
        $data = $this->api->tampil_semua_data();
        return $data;
        unset($data);
    }

    public function tampil_data($id_barang)
    {
        $data = $this->api->tampil_data($id_barang);
        return $data;
        unset($id_barang, $data);
    }

    public function tambah_data($data)
    {
        header('location:index.php?page=daftar-data');
        $this->api->tambah_data($data); //this is line 23
        // var_dump($response);
        unset($data);
    }
    public function ubah_data($data)
    {
        header('location:index.php?page=daftar-data');

        $this->api->ubah_data($data);
        unset($data);
    }

    public function hapus_data($id_barang)
    {

        header('location:index.php?page=daftar-data');
        $this->api->hapus_data($id_barang);
        unset($id_barang);
    }

    public function __destruct()
    {
        unset($this->api);
    }
}

$url = "http://192.168.56.2/wsdl-toko/server/server.php?wsdl";
$abc = new Client($url);
