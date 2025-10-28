<?php
include "client.php";

if ($_POST['aksi']=='tambah')
{	$data['aksi']=$_POST['aksi'];
	$data['nim']=$_POST['nim']; 
	$data['nama']=$_POST['nama']; 
	$data['no_hp'] = isset($_POST['no_hp']) ? $_POST['no_hp'] : '';
	$data['email'] = isset($_POST['email']) ? $_POST['email'] : '';
	$data['alamat'] = isset($_POST['alamat']) ? $_POST['alamat'] : '';
	$bb->tambah_mhs($data);
	header('location:index.php?page=daftar-data-server'); 
	unset($data);

} else if ($_POST['aksi']=='ubah')
{	$data['aksi']=$_POST['aksi'];
	$data['nim']=$_POST['nim']; 
	$data['nama']=$_POST['nama']; 
	$data['no_hp'] = isset($_POST['no_hp']) ? $_POST['no_hp'] : '';
	$data['email'] = isset($_POST['email']) ? $_POST['email'] : '';
	$data['alamat'] = isset($_POST['alamat']) ? $_POST['alamat'] : '';
	$bb->ubah_mhs($data);
	header('location:index.php?page=daftar-data-server'); 
	unset($data);

} else if ($_GET['aksi']=='hapus')
{	$data['aksi']=$_GET['aksi'];
	$data['nim']=$_GET['nim']; 
	$bb->hapus_mhs($data);
	header('location:index.php?page=daftar-data-server'); 
	unset($data);

} else if ($_POST['aksi']=='sinkronisasi')
{	$bb->sinkronisasi();
	header('location:index.php?page=daftar-data-client'); 
} 
?>
