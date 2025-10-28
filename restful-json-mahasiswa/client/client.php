<?php
error_reporting(1);

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

	public function filter($data)
	{
		$data = preg_replace('/[^a-zA-Z0-9]/', '', $data);
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

	public function tampil_data($nim)
	{
		$nim = $this->filter($nim);
		$client = curl_init($this->url . "?aksi=tampil&nim=" . $nim);
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
			'nim' => $data['nim'],
			'nama' => $data['nama'],
			'no_hp' => $data['no_hp'],
			'email' => $data['email'],
			'alamat' => $data['alamat'],
			'aksi' => $data['aksi']
		]);

		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $this->url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_POST, true);
		curl_setopt($c, CURLOPT_POSTFIELDS, $data);
		curl_exec($c);
		curl_close($c);
		unset($data, $c);
	}

	public function ubah_data($data)
	{
		$data = json_encode([
			'nim' => $data['nim'],
			'nama' => $data['nama'],
			'no_hp' => $data['no_hp'],
			'email' => $data['email'],
			'alamat' => $data['alamat'],
			'aksi' => $data['aksi']
		]);

		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $this->url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_POST, true);
		curl_setopt($c, CURLOPT_POSTFIELDS, $data);
		curl_exec($c);
		curl_close($c);
		unset($data, $c);
	}

	public function hapus_data($data)
	{
		$nim = $this->filter($data['nim']);
		$data = json_encode([
			'nim' => $nim,
			'aksi' => $data['aksi']
		]);

		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $this->url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_POST, true);
		curl_setopt($c, CURLOPT_POSTFIELDS, $data);
		curl_exec($c);
		curl_close($c);
		unset($nim, $data, $c);
	}

	public function sinkronisasi()
	{
		// hapus data lama di client
		$query = $this->conn->prepare("DELETE FROM mahasiswa");
		$query->execute();
		$query->closeCursor();

		// ambil data dari server
		$client = curl_init($this->url);
		curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($client);
		curl_close($client);
		$data = json_decode($response);

		// masukkan data server ke client
		foreach ($data as $r) {
			$query = $this->conn->prepare("INSERT INTO mahasiswa (nim, nama, no_hp, email, alamat) VALUES (?, ?, ?, ?, ?)");
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

$url = 'http://192.168.56.2/restful-json-mahasiswa/server/server.php';
$abc = new Client($url);
?>