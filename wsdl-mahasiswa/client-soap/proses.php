<?php
include "client.php";

if ($_POST['aksi'] == 'tambah') {
	$data = array(
		"nim" => $_POST['nim'],
		"nama" => $_POST['nama'],
		"no_hp" => $_POST['no_hp'],
		"email" => $_POST['email'],
		"alamat" => $_POST['alamat']
	);
	$objek->tambah_data($data);
	header('location:index.php?page=daftar-server');

} else if ($_POST['aksi'] == 'ubah') {
	$data = array(
		"nim" => $_POST['nim'],
		"nama" => $_POST['nama'],
		"no_hp" => $_POST['no_hp'],
		"email" => $_POST['email'],
		"alamat" => $_POST['alamat']
	);
	$objek->ubah_data($data);
	header('location:index.php?page=daftar-server');

} else if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
	$objek->hapus_data($_GET['nim']);
	header('location:index.php?page=daftar-server');

} else if ($_POST['aksi'] == 'sinkronisasi') {
	$objek->sinkronisasi();
	header('location:index.php?page=daftar-client');
}

unset($data, $objek);
?>