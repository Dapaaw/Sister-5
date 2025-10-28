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
        $data = simplexml_load_string($response);

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
        $data = simplexml_load_string($response);
        return $data;
        unset($data, $client, $response);
    }

    public function tambah_data($data)
    {
        $data = "<toko>
                    <barang>
                        <id_barang>" . $data['id_barang'] . "</id_barang>
                        <nama_barang>" . $data['nama_barang'] . "</nama_barang>
                        <aksi>" . $data['aksi'] . "</aksi>
                    </barang>
            </toko>";
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
        $data = "<toko>
                    <barang>
                        <id_barang>" . $data['id_barang'] . "</id_barang>
                        <nama_barang>" . $data['nama_barang'] . "</nama_barang>
                        <aksi>" . $data['aksi'] . "</aksi>
                    </barang>
            </toko>";

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $this->url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($c);
        curl_close($c);
        unset($data, $c, $response);
    }
    public function hapus_data($id_barang)
    {
        $data = "<toko>
                    <barang>
                        <id_barang>" . $id_barang . "</id_barang>
                        <aksi>hapus</aksi>
                    </barang>
            </toko>";
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $this->url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($c);
        $info = curl_getinfo($c);
        curl_close($c);
        var_dump($info);
        unset($id_barang, $data, $c, $response);
    }

    public function __destruct()
    {
        unset($this->option, $this->api);
    }
}
$url = 'http://192.168.56.2/restful-xml-toko/server/server.php';
$abc = new Client($url);
