<?php
error_reporting(1);
require_once('nusoap.php');

class Client
{
	private $host = "localhost";
	private $dbname = "serviceclient";
	private $api;

	private $driver = "mysql";
	private $user = "root";
	private $password = "";
	private $port = "3306";

	public function __construct($api)
	{
		// buat objek NuSOAP client
		$this->api = new nusoap_client($api, true);

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
		unset($api);
	}

	// ambil semua data mahasiswa dari server
	public function tampil_semua_data()
	{
		$data = $this->api->call('tampil_semua_data');
		return $data;
		unset($data);
	}

	// ambil 1 data mahasiswa berdasarkan NIM
	public function tampil_data($nim)
	{
		$data = $this->api->call('tampil_data', array($nim));
		return $data;
		unset($nim, $data);
	}

	// tambah data mahasiswa ke server
	public function tambah_data($data)
	{
		$this->api->call('tambah_data', array($data));
		unset($data);
	}

	// ubah data mahasiswa di server
	public function ubah_data($data)
	{
		$this->api->call('ubah_data', array($data));
		unset($data);
	}

	// hapus data mahasiswa di server
	public function hapus_data($nim)
	{
		$this->api->call('hapus_data', array($nim));
		unset($nim);
	}

	// sinkronisasi data dari server ke database client
	public function sinkronisasi()
	{
		$query = $this->conn->prepare("DELETE FROM mahasiswa");
		$query->execute();
		$query->closeCursor();

		$data = $this->api->call('tampil_semua_data');

		foreach ($data as $r) {
			$query = $this->conn->prepare("INSERT INTO mahasiswa (nim, nama, no_hp, email, alamat) VALUES (?,?,?,?,?)");
			$query->execute(array($r['nim'], $r['nama'], $r['no_hp'], $r['email'], $r['alamat']));
			$query->closeCursor();
		}

		unset($data, $r);
	}

	// ambil data mahasiswa lokal (di client)
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
		unset($this->api);
	}
}

// alamat WSDL server
$api = "http://192.168.56.2/wsdl-mahasiswa/server/server.php?wsdl";

// buat objek client
$objek = new Client($api);
?>