<?php
error_reporting(1); // error ditampilkan
class Client
{
	private $host = "localhost";
	private $dbname = "serviceclient";
	private $conn;
	private $url;


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
		// mengembalikan data
		return $data;
		// menghapus variable dari memory
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
		$data = '{	"nim":"' . $data['nim'] . '",
					"nama":"' . $data['nama'] . '",
					"no_hp": "' . $data['no_hp'] . '",
					"email": "' . $data['email'] . '",
					"alamat": "' . $data['alamat'] . '",
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
		$data = '{	"nim":"' . $data['nim'] . '",
					"nama":"' . $data['nama'] . '",
					"no_hp": "' . $data['no_hp'] . '",
					"email": "' . $data['email'] . '",
					"alamat": "' . $data['alamat'] . '",
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
		$nim = $this->filter($data['nim']);
		$data = '{	"nim":"' . $nim . '",
					"aksi":"' . $data['aksi'] . '"
				}';
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $this->url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_POST, true);
		curl_setopt($c, CURLOPT_POSTFIELDS, $data);
		$response = curl_exec($c);
		curl_close($c);
		unset($nim, $data, $c, $response);
	}

	public function sinkronisasi()
	{
		$query = $this->conn->prepare("delete from mahasiswa");
		$query->execute();
		$query->closeCursor();

		$client = curl_init($this->url);
		curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($client);
		curl_close($client);
		$data = json_decode($response);

		foreach ($data as $r) {
			$query = $this->conn->prepare("insert into mahasiswa (nim,nama,no_hp,email,alamat) values (?,?,?,?,?)");
			$query->execute(array($r->nim, $r->nama, $r->no_hp, $r->email, $r->alamat));

			$query->closeCursor();
		}

		unset($client, $response, $data, $r);
	}

	public function daftar_mhs_client()
	{	// query 
		$query = $this->conn->prepare("select nim,nama,no_hp,email,alamat from mahasiswa order by nim");
		$query->execute();

		$data = $query->fetchAll(PDO::FETCH_ASSOC);

		return $data;

		$query->closeCursor();
		// atau bisa menggunakan
		// $query = null;

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