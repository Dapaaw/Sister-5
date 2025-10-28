<?php
error_reporting(1); // error ditampilkan
class Client
{
	private $host = "localhost";
	private $dbname = "serviceclient";
	private $conn;
	private $url;

	// koneksi ke database mysql di client
	private $driver = "mysql";
	private $user = "root";
	private $password = "";
	private $port = "3306";

	/*
	// koneksi ke database postgresql di client
	private $driver="pgsql";
	private $user="postgres";
	private $password="postgres";
	private $port="5432";
	*/

	// diload pertama kali
	public function __construct($url)
	{
		$this->url = $url;
		try {
			if ($this->driver == 'mysql') {
				$this->conn = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8", $this->user, $this->password);
			} elseif ($this->driver == 'pgsql') {
				$this->conn = new PDO("pgsql:host=$this->host;port=$this->port;dbname=$this->dbname;user=$this->user;password=$this->password");
			}
		} catch (PDOException $e) {
			echo "Koneksi gagal";
		}

		unset($url);
	}

	public function login($data)
	{
		$data = json_encode([
			"id_pengguna" => $data['id_pengguna'],
			"pin" => $data['pin'],
			"aksi" => $data['aksi']
		]);

		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $this->url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_POST, true);
		curl_setopt($c, CURLOPT_POSTFIELDS, $data);
		$response = curl_exec($c);
		curl_close($c);
		$data2 = json_decode($response);
		return $data2;
		unset($data, $data2, $c, $response);
	}

	public function filter($data)
	{
		$data = preg_replace('/[^a-zA-Z0-9]/', '', $data);
		return $data;
		unset($data);
	}

	public function tampil_semua_data($jwt)
	{
		$client = curl_init($this->url . "?jwt=" . $jwt);
		curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($client);
		curl_close($client);
		$data = json_decode($response);
		return $data;
		unset($jwt, $client, $response, $data);
	}

	public function tampil_data($data)
	{
		$nim = $this->filter($data['nim']);
		$client = curl_init($this->url . "?aksi=tampil&nim=" . $nim . "&jwt=" . $data['jwt']);
		curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($client);
		curl_close($client);
		$data = json_decode($response);
		return $data;
		unset($nim, $client, $response, $data);
	}

	public function tambah_data($data)
	{
		$data = json_encode([
			"nim" => $data['nim'],
			"nama" => $data['nama'],
			"no_hp" => $data['no_hp'],
			"email" => $data['email'],
			"alamat" => $data['alamat'],
			"jwt" => $data['jwt'],
			"aksi" => $data['aksi']
		]);

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
		$data = json_encode([
			"nim" => $data['nim'],
			"nama" => $data['nama'],
			"no_hp" => $data['no_hp'],
			"email" => $data['email'],
			"alamat" => $data['alamat'],
			"jwt" => $data['jwt'],
			"aksi" => $data['aksi']
		]);

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
		$nim = $this->filter($data['nim']);
		$data = json_encode([
			"nim" => $nim,
			"jwt" => $data['jwt'],
			"aksi" => $data['aksi']
		]);

		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $this->url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_POST, true);
		curl_setopt($c, CURLOPT_POSTFIELDS, $data);
		$response = curl_exec($c);
		curl_close($c);
		unset($nim, $data, $c, $response);
	}

	public function sinkronisasi($jwt)
	{
		$query = $this->conn->prepare("DELETE FROM mahasiswa");
		$query->execute();
		$query->closeCursor();

		$client = curl_init($this->url . "?jwt=" . $jwt);
		curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($client);
		curl_close($client);
		$data = json_decode($response);

		foreach ($data as $r) {
			$query = $this->conn->prepare("INSERT INTO mahasiswa (nim, nama, no_hp, email, alamat) VALUES (?,?,?,?,?)");
			$query->execute([$r->nim, $r->nama, $r->no_hp, $r->email, $r->alamat]);
			$query->closeCursor();
		}

		unset($client, $response, $data, $r);
	}

	public function daftar_mhs_client()
	{
		$query = $this->conn->prepare("SELECT nim, nama, no_hp, email, alamat FROM mahasiswa ORDER BY nim");
		$query->execute();
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
		$query->closeCursor();
		unset($data);
	}

	public function __destruct()
	{
		unset($this->url);
	}
}

$url = 'http://192.168.56.2/jwt-mahasiswa/server/server.php';
$abc = new Client($url);
?>