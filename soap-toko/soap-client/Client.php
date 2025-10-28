<?php
error_reporting(1);
class Client
{
    private $option, $api;

    public function __construct($uri, $location)
    {
        $this->option = array('location' => $location, 'uri' => $uri);

        $this->api = new SoapClient(NULL, $this->option);

        unset($uri, $location);
    }

    public function filter($data)
    {
        $data = preg_replace('/[^a-zA-Z0-9]/', '', $data);
        return $data;
        unset($data);
    }

    public function tampil_semua_data()
    {
        $data = $this->api->tampil_semua_data();
        return $data;
        unsert($data);
    }

    public function tampil_data($id_barang)
    {
        $id_barang = $this->filter($id_barang);
        $data = $this->api->tampil_data($id_barang);
        return $data;
        unset($id_barang, $data);
    }

    public function tambah_data($data)
    {
        $this->api->tambah_data($data);
        unset($data);
    }

    public function ubah_data($data)
    {
        $this->api->ubah_data($data);
        unset($data);
    }

    public function hapus_data($id_barang)
    {
        $this->api->hapus_data($id_barang);
        unset($data);
    }

    public function __destruct()
    {
        unset($this->option, $this->api);
    }
}

$uri = 'http://192.168.56.2';
$location = $uri . '/soap-toko/soap-server/server.php';
$abc = new Client($uri, $location);
