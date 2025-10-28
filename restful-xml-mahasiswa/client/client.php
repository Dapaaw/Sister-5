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

	public function __construct($url)
	{
		$this->url = $url;

		// koneksi database lokal client
		try {
			if ($this->driver == 'mysql') {
				$this->conn = new PDO(
					"mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8",
					$this->user,
					$this->password
				);
			} elseif ($this->driver == 'pgsql') {
				$this->conn = new PDO(
					"pgsql:host=$this->host;port=$this->port;dbname=$this->dbname;user=$this->user;password=$this->password"
				);
			}
		} catch (PDOException $e) {
			echo "Koneksi gagal";
		}

		unset($url);
	}

	// membersihkan data
	public function filter($data)
	{
		$data = preg_replace('/[^a-zA-Z0-9]/', '', $data);
		return $data;
		unset($data);
	}

	// ambil semua data dari server (XML)
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

	// ambil data berdasarkan NIM
	public function tampil_data($nim)
	{
		$nim = $this->filter($nim);
		$client = curl_init($this->url . "?aksi=tampil&nim=" . $nim);
		curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($client);
		curl_close($client);
		$data = simplexml_load_string($response);
		return $data;
		unset($nim, $client, $response, $data);
	}

	// tambah data ke server
	public function tambah_data($data)
	{
		$data_xml = "
			<uinmalang>
				<mahasiswa>
					<nim>{$data['nim']}</nim>
					<nama>{$data['nama']}</nama>
					<no_hp>{$data['no_hp']}</no_hp>
					<email>{$data['email']}</email>
					<alamat>{$data['alamat']}</alamat>
					<aksi>{$data['aksi']}</aksi>
				</mahasiswa>
			</uinmalang>";

		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $this->url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_POST, true);
		curl_setopt($c, CURLOPT_POSTFIELDS, $data_xml);
		$response = curl_exec($c);
		curl_close($c);
		unset($data_xml, $c, $response);
	}

	// ubah data ke server
	public function ubah_data($data)
	{
		$data_xml = "
			<uinmalang>
				<mahasiswa>
					<nim>{$data['nim']}</nim>
					<nama>{$data['nama']}</nama>
					<no_hp>{$data['no_hp']}</no_hp>
					<email>{$data['email']}</email>
					<alamat>{$data['alamat']}</alamat>
					<aksi>{$data['aksi']}</aksi>
				</mahasiswa>
			</uinmalang>";

		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $this->url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_POST, true);
		curl_setopt($c, CURLOPT_POSTFIELDS, $data_xml);
		$response = curl_exec($c);
		curl_close($c);
		unset($data_xml, $c, $response);
	}

	// hapus data di server
	public function hapus_data($nim)
	{
		$nim = $this->filter($nim);
		$data = "
			<uinmalang>
				<mahasiswa>
					<nim>{$nim}</nim>
					<aksi>hapus</aksi>
				</mahasiswa>
			</uinmalang>";

		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $this->url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_POST, true);
		curl_setopt($c, CURLOPT_POSTFIELDS, $data);
		$response = curl_exec($c);
		curl_close($c);
		unset($nim, $data, $c, $response);
	}

	// sinkronisasi data server → client
	public function sinkronisasi()
	{
		// hapus semua data lama di client
		$query = $this->conn->prepare("DELETE FROM mahasiswa");
		$query->execute();
		$query->closeCursor();

		// ambil data dari server
		$client = curl_init($this->url);
		curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($client);
		curl_close($client);
		$data = simplexml_load_string($response);

		// simpan data ke database client
		foreach ($data as $r) {
			$query = $this->conn->prepare(
				"INSERT INTO mahasiswa (nim, nama, no_hp, email, alamat) VALUES (?,?,?,?,?)"
			);
			$query->execute([
				$r->nim,
				$r->nama,
				$r->no_hp,
				$r->email,
				$r->alamat
			]);
			$query->closeCursor();
		}

		unset($client, $response, $data, $r);
	}

	// tampilkan data dari database lokal client
	public function daftar_mhs_client()
	{
		$query = $this->conn->prepare("SELECT nim,nama,no_hp,email,alamat FROM mahasiswa ORDER BY nim");
		$query->execute();
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
		$query->closeCursor();
		unset($data);
	}

	public function __destruct()
	{
		unset($this->options, $this->api);
	}
}

// URL server RESTful XML
$url = 'http://192.168.56.2/restful-xml-mahasiswa/server/server.php';

// buat objek baru dari class Client
$abc = new Client($url);
?>