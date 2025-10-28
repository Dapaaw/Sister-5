<?php
error_reporting(1); // error ditampilkan
class Client
{
	private $host = "localhost";
	private $dbname = "serviceclient";
	private $conn, $options, $api;

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

	// function yang pertama kali di-load saat class dipanggil
	public function __construct($uri, $location)
	{	// set uri SOAP server
		$this->options = array('uri' => $uri, 'location' => $location);
		// buat objek baru dari class SOAP Client
		$this->api = new SoapClient(NULL, $this->options);
		// koneksi database lokal client
		try {
			if ($this->driver == 'mysql') {
				$this->conn = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8", $this->user, $this->password);
			} elseif ($this->driver == 'pgsql') {
				$this->conn = new PDO("pgsql:host=$this->host;port=$this->port;dbname=$this->dbname;user=$this->user;password=$this->password");
			}
		} catch (PDOException $e) {
			echo "Koneksi gagal";
		}
		unset($uri, $location);
	}

	// function untuk menghapus selain huruf dan angka
	public function filter($data)
	{
		$data = preg_replace('/[^a-zA-Z0-9]/', '', $data);
		return $data;
		unset($data);
	}

	public function tampil_semua_data()
	{
		$data = $this->api->tampil_semua_data();
		return $data;
		unset($data);
	}

	public function tampil_data($nim)
	{
		$nim = $this->filter($nim);
		$data = $this->api->tampil_data($nim);
		return $data;
		unset($nim, $data);
	}

	public function tambah_data($data)
	{
		$this->api->tambah_data($data);
		unset($data);
	}

	public function ubah_data($data)
	{
		$this->api->ubah_data($data);
		unset($data);
	}

	public function hapus_data($nim)
	{
		$this->api->hapus_data($nim);
		unset($nim);
	}

	public function sinkronisasi()
	{	// query ke lokal database client
		$query = $this->conn->prepare("delete from mahasiswa");
		$query->execute();
		// menghapus query dari memory
		$query->closeCursor();
		// memanggil method/fungsi yang ada di server dan dimasukkan ke variabel $data
		$data = $this->api->tampil_semua_data();
		foreach ($data as $r) {	// query ke lokal database client
			$query = $this->conn->prepare("insert into mahasiswa (nim,nama,no_hp,email,alamat) values (?,?,?,?,?)");
			$query->execute(
				array(
					isset($r['nim']) ? $r['nim'] : '',
					isset($r['nama']) ? $r['nama'] : '',
					isset($r['no_hp']) ? $r['no_hp'] : '',
					isset($r['email']) ? $r['email'] : '',
					isset($r['alamat']) ? $r['alamat'] : ''
				)
			);
			$query->closeCursor();

		}
		unset($data, $r);
	}

	public function daftar_mhs_client()
	{
		$query = $this->conn->prepare("select nim, nama, no_hp, email, alamat from mahasiswa order by nim");
		$query->execute();
		// mengambil banyak record data dengan fetchAll()
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		// mengembalikan data
		return $data;
		// menghapus variable dari memory 
		$query->closeCursor();
		unset($data);
		// atau dapat menggunakan
		// $query=null;
		// $data=null;
	}

	// function yang terakhir kali di-load saat class dipanggil
	public function __destruct()
	{
		unset($this->options, $this->api);
	}
}

// uri dan location server
$uri = 'http://192.168.56.2';
$location = $uri . '/soap-mahasiswa/server/server.php';
// buat objek baru dari class Client
$objek = new Client($uri, $location);
?>