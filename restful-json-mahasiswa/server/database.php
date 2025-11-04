<?php
class Database
{
	private $host = "localhost";
	private $dbname = "serviceserver";
	private $user = "root";
	private $password = "";
	private $port = "3306";
	private $conn;

	// Koneksi otomatis ketika class di-load
	public function __construct()
	{
		try {
			$this->conn = new PDO(
				"mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8",
				$this->user,
				$this->password
			);
		} catch (PDOException $e) {
			echo "Koneksi gagal: " . $e->getMessage();
		}
	}

	// Filter hanya huruf dan angka
	public function filter($data)
	{
		$data = preg_replace('/[^a-zA-Z0-9]/', '', $data);
		return $data;
		unset($data);
	}

	// Tampilkan semua data mahasiswa
	public function tampil_semua_data()
	{
		$query = $this->conn->prepare("SELECT nim, nama, no_hp, email, alamat FROM mahasiswa ORDER BY nim");
		$query->execute();
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
		$query->closeCursor();
		unset ($data);
	}

	// Tampilkan satu data berdasarkan NIM
	public function tampil_data($nim)
	{
		$query = $this->conn->prepare("SELECT nim, nama, no_hp, email, alamat FROM mahasiswa WHERE nim = ?");
		$query->execute(array($nim));
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$query->closeCursor();
		return $data;
	}

	// Tambah data mahasiswa baru
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

	// Ubah data mahasiswa
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

	public function hapus_data($nim)
	{
		$query = $this->conn->prepare("DELETE FROM mahasiswa WHERE nim = ?");
		$query->execute(array($nim));
		$query->closeCursor();
		unset($nim);
	}
}
?>