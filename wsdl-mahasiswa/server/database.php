<?php
class Database
{
	private $host = "localhost";
	private $dbname = "serviceserver";
	private $conn;

	// koneksi ke database mysql di server
	private $driver = "mysql";
	private $user = "root";
	private $password = "";
	private $port = "3306";

	/*
	// koneksi ke database postgresql di server
	private $driver = "pgsql";
	private $user = "postgres";
	private $password = "postgres";
	private $port = "5432";
	*/

	// function yang pertama kali di-load saat class dipanggil
	public function __construct()
	{
		try {
			if ($this->driver == 'mysql') {
				$this->conn = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8", $this->user, $this->password);
			} elseif ($this->driver == 'pgsql') {
				$this->conn = new PDO("pgsql:host=$this->host;port=$this->port;dbname=$this->dbname;user=$this->user;password=$this->password");
			}
		} catch (PDOException $e) {
			echo "Koneksi gagal";
		}
	}

	// Menampilkan semua data mahasiswa
	public function tampil_semua_data()
	{
		$query = $this->conn->prepare("SELECT nim, nama, no_hp, email, alamat FROM mahasiswa ORDER BY nim");
		$query->execute();
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
		$query->closeCursor();
		unset($data);
	}

	// Menampilkan data berdasarkan NIM
	public function tampil_data($nim)
	{
		$query = $this->conn->prepare("SELECT nim, nama, no_hp, email, alamat FROM mahasiswa WHERE nim = ?");
		$query->execute(array($nim));
		$data = $query->fetch(PDO::FETCH_ASSOC);
		return $data;
		$query->closeCursor();
		unset($nim, $data);
	}

	// Menambah data mahasiswa baru
	public function tambah_data($data)
	{
		$query = $this->conn->prepare("INSERT IGNORE INTO mahasiswa (nim, nama, no_hp, email, alamat) VALUES (?, ?, ?, ?, ?)");
		$query->execute(array(
			$data['nim'],
			$data['nama'],
			$data['no_hp'],
			$data['email'],
			$data['alamat']
		));
		$query->closeCursor();
		unset($data);
	}

	// Mengubah data mahasiswa
	public function ubah_data($data)
	{
		$query = $this->conn->prepare("UPDATE mahasiswa SET nama = ?, no_hp = ?, email = ?, alamat = ? WHERE nim = ?");
		$query->execute(array(
			$data['nama'],
			$data['no_hp'],
			$data['email'],
			$data['alamat'],
			$data['nim']
		));
		$query->closeCursor();
		unset($data);
	}

	// Menghapus data mahasiswa
	public function hapus_data($nim)
	{
		$query = $this->conn->prepare("DELETE FROM mahasiswa WHERE nim = ?");
		$query->execute(array($nim));
		$query->closeCursor();
		unset($nim);
	}
}
?>