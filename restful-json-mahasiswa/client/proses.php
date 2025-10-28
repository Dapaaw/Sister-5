<?php
include "client.php";

if ($_POST['aksi'] == 'tambah') {
	$data = array(
		"nim" => $_POST['nim'],
		"nama" => $_POST['nama'],
		"no_hp" => $_POST['no_hp'],
		"email" => $_POST['email'],
		"alamat" => $_POST['alamat'],
		"aksi" => $_POST['aksi']
	);
	$abc->tambah_data($data);
	header('location:index.php?page=data-server');

} else if ($_POST['aksi'] == 'ubah') {
	$data = array(
		"nim" => $_POST['nim'],
		"nama" => $_POST['nama'],
		"no_hp" => $_POST['no_hp'],
		"email" => $_POST['email'],
		"alamat" => $_POST['alamat'],
		"aksi" => $_POST['aksi']
	);
	$abc->ubah_data($data);
	header('location:index.php?page=data-server');

} else if ($_GET['aksi'] == 'hapus') {
	$data = array(
		"nim" => $_GET['nim'],
		"aksi" => $_GET['aksi']
	);
	$abc->hapus_data($data);
	header('location:index.php?page=data-server');

} else if ($_POST['aksi'] == 'sinkronisasi') {
	$abc->sinkronisasi();
	header('location:index.php?page=data-client');
}

unset($data, $abc);
?>