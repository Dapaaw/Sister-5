<?php
error_reporting(1); // value 1 error ditampilkan, value 0 error tidak ditampilkan

class client
{ 	private $host="localhost";
	private $dbname="serviceclient";
	private $conn, $url;
	private $dbAvailable = false;
	
	// koneksi ke database mysql di client
	private $driver="mysql";
	private $user="root";
	private $password="";
	private $port="3306";
	
	/*
	// koneksi ke database postgresql di client
	private $driver="pgsql";
	private $user="postgres";
	private $password="postgres";
	private $port="5432";
	*/

	// function yang pertama kali di-load saat class dipanggil
	public function __construct($url)
	{ 	$this->url = $url;
		// koneksi database lokal client
		try
		{ 	if ($this->driver == 'mysql')
			{ 	$this->conn = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8",$this->user,$this->password);
				$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->dbAvailable = true;
			} elseif ($this->driver == 'pgsql')
			{ 	$this->conn = new PDO("pgsql:host=$this->host;port=$this->port;dbname=$this->dbname;user=$this->user;password=$this->password");
				$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->dbAvailable = true;
			}    
		} catch (PDOException $e)
		{ 	echo "Koneksi gagal";         
			// tampilkan pesan error untuk debugging koneksi
			echo ": " . $e->getMessage();
		}
		// menghapus variabel dari memory
		unset($url);
	}    

	// fungsi menghapus selain huruf dan angka
	public function filter($data)
	{	$data = preg_replace('/[^a-zA-Z0-9]/', '', $data);
		return $data;
	}

	public function daftar_mhs_server()
	{	$context = stream_context_create(array('http' => array(
	    'method' => "GET",
	    'header' => "Content-Type:text/xml;charset=UTF-8"
		)));
		$response = file_get_contents($this->url, false, $context);
		$data = xmlrpc_decode($response);		
		// mengembalikan data
		return $data;
	}
	
	public function tampil_mhs($nim)
	{	$nim = $this->filter($nim);
		$context = stream_context_create(array('http' => array(
	    'method' => "GET",
	    'header' => "Content-Type:text/xml;charset=UTF-8"
		)));
		$response = file_get_contents($this->url."?nim=".$nim."&aksi=tampil", false, $context);
		$data = xmlrpc_decode($response); 		
		return $data; 			
	}

	public function tambah_mhs($data)
	{ 	// build payload with additional fields
		$data = xmlrpc_encode_request("method",array(
			"nim" => $data['nim'],
			"nama" => $data['nama'],
			"no_hp" => isset($data['no_hp']) ? $data['no_hp'] : '',
			"email" => isset($data['email']) ? $data['email'] : '',
			"alamat" => isset($data['alamat']) ? $data['alamat'] : '',
			"aksi" => $data['aksi']
		));
		$context = stream_context_create(array('http' => array(
		    'method' => "POST",
		    'header' => "Content-Type:text/xml;charset=UTF-8",
		    'content' => $data
		)));
		$file = file_get_contents($this->url, false, $context);
		xmlrpc_decode($file);
		unset($data,$context,$file);			
	}
	
	public function ubah_mhs($data)
	{ 	// build payload with additional fields
		$data = xmlrpc_encode_request("method",array(
			"nim" => $data['nim'],
			"nama" => $data['nama'],
			"no_hp" => isset($data['no_hp']) ? $data['no_hp'] : '',
			"email" => isset($data['email']) ? $data['email'] : '',
			"alamat" => isset($data['alamat']) ? $data['alamat'] : '',
			"aksi" => $data['aksi']
		));
		$context = stream_context_create(array('http' => array(
		    'method' => "POST",
		    'header' => "Content-Type:text/xml;charset=UTF-8",
		    'content' => $data
		)));
		$file = file_get_contents($this->url, false, $context);
		xmlrpc_decode($file);
		unset($data,$context,$file);			
	}

	public function hapus_mhs($data)
	{	$data = xmlrpc_encode_request("method",array("nim"=>$data['nim'],"aksi"=>$data['aksi']));
		$context = stream_context_create(array('http' => array(
		    'method' => "POST",
		    'header' => "Content-Type:text/xml;charset=UTF-8",
		    'content' => $data
		)));
		$file = file_get_contents($this->url, false, $context);
		xmlrpc_decode($file);
		unset($data,$context,$file);			
	}

	public function sinkronisasi()
	{	// query ke lokal database client
		if (!$this->dbAvailable || $this->conn === null) {
			// database tidak tersedia di client lokal, hentikan sinkronisasi
			return false;
		}
		$query = $this->conn->prepare("delete from mahasiswa");
		$query->execute();
		$query->closeCursor();

			// build payload with additional fields
		// mengambil data semua mahasiswa di server dan disimpan di $data
		$context = stream_context_create(array('http' => array(
	    'method' => "GET",
	    'header' => "Content-Type:text/xml;charset=UTF-8"
		)));
		$response = file_get_contents($this->url, false, $context);
		$data = xmlrpc_decode($response);
		// looping $data dan masukkan ke dalam database client 
		foreach ($data as $r) 	
		{ 	// query insert data ke lokal database client (termasuk no_hp,email,alamat)
			$query = $this->conn->prepare("insert into mahasiswa (nim,nama,no_hp,email,alamat) values (?,?,?,?,?)"); 	
			$query->execute(array(
				isset($r['nim']) ? $r['nim'] : '',
				isset($r['nama']) ? $r['nama'] : '',
				isset($r['no_hp']) ? $r['no_hp'] : '',
				isset($r['email']) ? $r['email'] : '',
				isset($r['alamat']) ? $r['alamat'] : ''
			));
			$query->closeCursor(); 	
		} 		 
			// build payload with additional fields
		unset($context,$response,$data,$r);	
	}

	public function daftar_mhs_client()
	{ 	if (!$this->dbAvailable || $this->conn === null) {
			return array();
		}
		$query = $this->conn->prepare("select nim, nama, no_hp, email, alamat from mahasiswa order by nim");
		$query->execute(); 		
		// mengambil banyak record data dengan fetchAll()
		$data = $query->fetchAll(PDO::FETCH_ASSOC); 	
		$query->closeCursor(); 
		return $data; 
	}

	public function __destruct()
	{	unset($this->url);	
	}
}

$url = 'http://192.168.56.2/rpc-xml-mahasiswa/server/server.php';
// buat objek baru dari class Client
$bb = new client($url);
?>