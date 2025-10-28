<?php
// error_reporting(0);
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

    public function login($data)
    {
        $data = '{
                    "id_pengguna":"' . $data['id_pengguna'] . '",
                    "pin":"' . $data['pin'] . '",
                    "aksi":"' . $data['aksi'] . '"
                }';

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $this->url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($c);
        curl_close($c);
        $data2 = json_decode($response);
        return $data2;
        unset($data,$data2,$c,$response);
    }

    public function tampil_semua_data($jwt)
    {
        $client = curl_init($this->url.'?jwt='. $jwt);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($client);
        curl_close($client);  
        $data = json_decode($response);
        return $data;
        unset($jwt,$data, $client, $response);
    }

    public function tampil_data($data)
    {
        $id_barang = $this->filter($data['id_barang']);
        $client = curl_init($this->url . "?aksi=tampil&id_barang=" . $id_barang.'&jwt='.$data['jwt']);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($client);
        curl_close($client);
        $data = json_decode($response);
        return $data;
        unset($id_barang,$data, $client, $response);
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
        $response = curl_exec($c);
        curl_close($c);
        unset($data, $c, $response);
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
        $response = curl_exec($c);
        curl_close($c);
        unset($data, $c, $response);
    }
    public function hapus_data($data)
    {
        $id_barang = $this->filter($data['id_barang']);
        $data = '{
                    "id_barang":"' . $id_barang . '",
                    "jwt":"' . $data['jwt'] . '",
                    "aksi":"' . $data['aksi'] . '"
                }';

                echo"Client hapus data id : ".$data;
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
        unset($this->option);
    }


}
$url = 'http://192.168.56.2/jwt-toko2/server/server.php';
$abc = new Client($url);