<?php
error_reporting(1);
include "database.php";

// Buat objek database
$abc = new Database();

// ==== Handle CORS ====
if (isset($_SERVER['HTTP_ORIGIN'])) {
	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 86400'); // cache 1 hari
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
		header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	exit(0);
}

// ==== Ambil data JSON dari body ====
$postdata = file_get_contents("php://input");

// ==== METHOD POST ====
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$data = json_decode($postdata);
	$aksi = $data->aksi ?? '';
	$nim = $data->nim ?? '';
	$nama = $data->nama ?? '';
	$no_hp = $data->no_hp ?? '';
	$email = $data->email ?? '';
	$alamat = $data->alamat ?? '';

	if ($aksi == 'tambah') {
		$data2 = array(
			'nim' => $nim,
			'nama' => $nama,
			'no_hp' => $no_hp,
			'email' => $email,
			'alamat' => $alamat
		);
		$abc->tambah_data($data2);
		echo json_encode(['status' => 'sukses', 'pesan' => 'Data berhasil ditambahkan']);
	} elseif ($aksi == 'ubah') {
		$data2 = array(
			'nim' => $nim,
			'nama' => $nama,
			'no_hp' => $no_hp,
			'email' => $email,
			'alamat' => $alamat
		);
		$abc->ubah_data($data2);
		echo json_encode(['status' => 'sukses', 'pesan' => 'Data berhasil diubah']);
	} elseif ($aksi == 'hapus') {
		$abc->hapus_data($nim);
		echo json_encode(['status' => 'sukses', 'pesan' => 'Data berhasil dihapus']);
	} else {
		echo json_encode(['status' => 'gagal', 'pesan' => 'Aksi tidak dikenali']);
	}

	unset($postdata, $data, $data2, $nim, $nama, $no_hp, $email, $alamat, $aksi, $abc);
}

// ==== METHOD GET ====
elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if (isset($_GET['aksi']) && $_GET['aksi'] == 'tampil' && isset($_GET['nim'])) {
		$nim = $abc->filter($_GET['nim']);
		$data = $abc->tampil_data($nim);
		echo json_encode($data);
	} else {
		$data = $abc->tampil_semua_data();
		echo json_encode($data);
	}
	unset($postdata, $data, $nim, $abc);
}
?>