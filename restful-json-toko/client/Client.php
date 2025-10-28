<?php
error_reporting(1);
class Client
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
        unset($url);
    }
    public function filter($data)
    {
        $data = preg_replace('/[^a-zA-Z0-9]/', "", $data);
        return $data;
        unset($data);
    }

    public function tampil_semua_data()
    {
        $client = curl_init($this->url);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($client);
        curl_close($client);
        $data = json_decode($response);

        return $data;
        unset($data, $client, $response);
    }

    public function tampil_data($id_barang)
    {
        $id_barang = $this->filter($id_barang);
        $client = curl_init($this->url . "?aksi=tampil&id_barang=" . $id_barang);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($client);
        curl_close($client);
        $data = json_decode($response);
        return $data;
        unset($data, $client, $response);
    }

    public function tambah_data($data)
    {
        $data = '{
                    "id_barang":"' . $data['id_barang'] . '",
                    "nama_barang":"' . $data['nama_barang'] . '",
                    "aksi":"' . $data['aksi'] . '"       
                }';
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $this->url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($c);
        curl_close($c);
        unset($data, $c, $response);
    }
    public function ubah_data($data)
    {
        $data = '{
                    "id_barang":"' . $data['id_barang'] . '",
                    "nama_barang":"' . $data['nama_barang'] . '",
                    "aksi":"' . $data['aksi'] . '"       
                }';
        // var_dump($data);
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $this->url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($c);
        curl_close($c);
        unset($data, $c, $response);
    }
    public function hapus_data($data)
    {
        $id_barang = $this->filter($data['id_barang']);
        $data = '{
                    "id_barang":"' . $id_barang . '",
                    "aksi":"' . $data['aksi'] . '"
                }';
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $this->url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($c);
        curl_close($c);
        unset($id_barang, $data, $c, $response);
    }

    public function __destruct()
    {
        unset($this->option, $this->api);
    }
}
$url = 'http://192.168.56.2/restful-json-toko/server/server.php';
$abc = new Client($url);
