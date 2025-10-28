<?php
error_reporting(1);

class Client
{
	private $host = "localhost";
	private $dbname = "serviceclient";
	private $conn, $api;

	// Konfigurasi koneksi database lokal (client)
	private $driver = "mysql";
	private $user = "root";
	private $password = "";
	private $port = "3306";

	public function __construct($api)
	{
		$this->api = new SoapClient($api);

		try {
			if ($this->driver == 'mysql') {
				$this->conn = new PDO(
					"mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8",
					$this->user,
					$this->password
				);
			} elseif ($this->driver == 'pgsql') {
				$this->conn = new PDO(
					"pgsql:host={$this->host};port={$this->port};dbname={$this->dbname};user={$this->user};password={$this->password}"
				);
			}
		} catch (PDOException $e) {
			echo "Koneksi ke database client gagal: " . $e->getMessage();
		}
	}

	private function filter($data)
	{
		return preg_replace('/[^a-zA-Z0-9]/', '', $data);
	}

	// ======== Fungsi CRUD via SOAP Server ========
	public function tampil_semua_data()
	{
		return $this->api->tampil_semua_data();
	}

	public function tampil_data($nim)
	{
		$nim = $this->filter($nim);
		return $this->api->tampil_data($nim);
	}

	public function tambah_data($data)
	{
		$this->api->tambah_data($data);
	}

	public function ubah_data($data)
	{
		$this->api->ubah_data($data);
	}

	public function hapus_data($nim)
	{
		$nim = $this->filter($nim);
		$this->api->hapus_data($nim);
	}

	// ======== Sinkronisasi Data Server → Client ========
	public function sinkronisasi()
	{
		// Hapus semua data di tabel client
		$query = $this->conn->prepare("DELETE FROM mahasiswa");
		$query->execute();
		$query->closeCursor();

		// Ambil data dari server SOAP
		$data = $this->api->tampil_semua_data();

		// Masukkan ke tabel client
		foreach ($data as $r) {
			$query = $this->conn->prepare("INSERT INTO mahasiswa (nim, nama, no_hp, email, alamat) VALUES (?, ?, ?, ?, ?)");
			$query->execute([$r->nim, $r->nama, $r->no_hp, $r->email, $r->alamat]);
			$query->closeCursor();
		}
	}

	// ======== Menampilkan Data dari Database Client ========
	public function daftar_mhs_client()
	{
		$query = $this->conn->prepare("SELECT nim, nama, no_hp, email, alamat FROM mahasiswa ORDER BY nim");
		$query->execute();
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		$query->closeCursor();
		return $data;
	}

	public function __destruct()
	{
		$this->conn = null;
		$this->api = null;
	}
}

// Ubah sesuai IP Server SOAP kamu
$api = 'http://192.168.56.2/wsdl-mahasiswa/server/server.php?wsdl';

// Buat objek Client
$objek = new Client($api);
?>