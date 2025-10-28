<?php
error_reporting(1); // error ditampilkan
require_once('nusoap.php');
require_once('database.php');

// buat objek baru dari class NuSOAP Server
$server = new nusoap_server();

// konfigurasi WSDL
$server->configureWSDL('WSDL Mahasiswa', 'urn:ServerWSDL');

// --- Registrasi fungsi tampil_semua_data ---
$server->register(
	'tampil_semua_data',
	array(),
	array('return' => 'xsd:Array'),
	'urn:ServerWSDL',
	'urn:ServerWSDL#tampil_semua_data',
	'rpc',
	'encoded',
	'Menampilkan semua data mahasiswa'
);

// --- Registrasi fungsi tampil_data ---
$server->register(
	'tampil_data',
	array('nim' => 'xsd:string'),
	array('return' => 'xsd:Array'),
	'urn:ServerWSDL',
	'urn:ServerWSDL#tampil_data',
	'rpc',
	'encoded',
	'Menampilkan data mahasiswa berdasarkan NIM'
);

// --- Registrasi fungsi tambah_data ---
$server->register(
	'tambah_data',
	array('data' => 'xsd:Array'),
	array(),
	'urn:ServerWSDL',
	'urn:ServerWSDL#tambah_data',
	'rpc',
	'encoded',
	'Menambahkan data mahasiswa'
);

// --- Registrasi fungsi ubah_data ---
$server->register(
	'ubah_data',
	array('data' => 'xsd:Array'),
	array(),
	'urn:ServerWSDL',
	'urn:ServerWSDL#ubah_data',
	'rpc',
	'encoded',
	'Mengubah data mahasiswa'
);

// --- Registrasi fungsi hapus_data ---
$server->register(
	'hapus_data',
	array('nim' => 'xsd:string'),
	array(),
	'urn:ServerWSDL',
	'urn:ServerWSDL#hapus_data',
	'rpc',
	'encoded',
	'Menghapus data mahasiswa berdasarkan NIM'
);

// --- Fungsi bantu: hapus karakter non-huruf/angka ---
function filter($data)
{
	$data = preg_replace('/[^a-zA-Z0-9]/', '', $data);
	return $data;
	unset($data);
}

// --- Fungsi tampil semua data ---
function tampil_semua_data()
{
	$db = new Database();
	$data = $db->tampil_semua_data();
	return $data;
	unset($db, $data);
}

// --- Fungsi tampil data berdasarkan NIM ---
function tampil_data($nim)
{
	$nim = filter($nim);
	$db = new Database();
	$data = $db->tampil_data($nim);
	return $data;
	unset($nim, $db, $data);
}

// --- Fungsi tambah data ---
function tambah_data($data)
{
	$db = new Database();
	$db->tambah_data(array(
		'nim' => $data['nim'],
		'nama' => $data['nama'],
		'no_hp' => $data['no_hp'],
		'email' => $data['email'],
		'alamat' => $data['alamat']
	));
	unset($db, $data);
}

// --- Fungsi ubah data ---
function ubah_data($data)
{
	$db = new Database();
	$db->ubah_data(array(
		'nim' => $data['nim'],
		'nama' => $data['nama'],
		'no_hp' => $data['no_hp'],
		'email' => $data['email'],
		'alamat' => $data['alamat']
	));
	unset($db, $data);
}

// --- Fungsi hapus data ---
function hapus_data($nim)
{
	$nim = filter($nim);
	$db = new Database();
	$db->hapus_data($nim);
	unset($nim, $db);
}

// --- Jalankan service ---
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);

unset($server);
?>