<?php
class Client
{
    private $url;

    // ✅ Constructor dengan default URL
    public function __construct($url = "http://192.168.56.2/jwt-toko2/server/server.php")
    {
        $this->url = $url;
    }

    public function filter($data)
    {
        $data = preg_replace('/[^a-zA-Z0-9]/', "", $data);
        return $data;
    }

    public function login($data)
    {
        $data = '{
            "id_pengguna":"' . $data['id_pengguna'] . '",
            "pin":"' . $data['pin'] . '"
        }';

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $this->url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($c);
        curl_close($c);
        return json_decode($response);
    }

    public function tampil_semua_data($jwt)
    {
        $client = curl_init($this->url . '?jwt=' . $jwt);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($client);
        curl_close($client);
        return json_decode($response);
    }

    public function tampil_data($data)
    {
        $id_barang = $this->filter($data['id_barang']);
        $client = curl_init($this->url . "?aksi=tampil&id_barang=" . $id_barang . '&jwt=' . $data['jwt']);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($client);
        curl_close($client);
        return json_decode($response);
    }

    public function tambah_data($data)
    {
        $data = '{
            "id_barang":"' . $data['id_barang'] . '",
            "nama_barang":"' . $data['nama_barang'] . '",
            "jwt":"' . $data['jwt'] . '",
            "aksi":"' . $data['aksi'] . '"       
        }';
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $this->url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        curl_exec($c);
        curl_close($c);
    }

    public function ubah_data($data)
    {
        $data = '{
            "id_barang":"' . $data['id_barang'] . '",
            "nama_barang":"' . $data['nama_barang'] . '",
            "jwt":"' . $data['jwt'] . '",
            "aksi":"' . $data['aksi'] . '"       
        }';
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $this->url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        curl_exec($c);
        curl_close($c);
    }

    public function hapus_data($data)
    {
        $id_barang = $this->filter($data['id_barang']);
        $data = '{
            "id_barang":"' . $id_barang . '",
            "jwt":"' . $data['jwt'] . '",
            "aksi":"' . $data['aksi'] . '"
        }';
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $this->url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        curl_exec($c);
        curl_close($c);
    }
}
