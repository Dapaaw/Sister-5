<?php
error_reporting(1);
include("Client.php");

if ($_POST['aksi'] == 'login') {
    $data = array("id_pengguna" => $_POST['id_pengguna'], "pin" => $_POST['pin'], "aksi" => $_POST['aksi']);
    $data2 = $abc->login($data);


    if ($data2) {
        setcookie('jwt', $data2->jwt, time() + 60 * 60);
        setcookie('id_pengguna', $data2->id_pengguna, time() + 60 * 60);
        setcookie('nama', $data2->nama, time() + 60 * 60);
        header('location:index.php?page=daftar-data');
    } else {
        header('location:index.php?page=login');
    }
} elseif ($_POST['aksi'] == 'tambah') {
    $data = array("id_barang" => $_POST['id_barang'], "nama_barang" => $_POST['nama_barang'], "jwt" => $_POST['jwt'], "aksi" => $_POST['aksi']);
    $abc->tambah_data($data);
    header('location:index.php?page=daftar-data');
} elseif ($_POST['aksi'] == 'ubah') {
    $data = array("id_barang" => $_POST["id_barang"], "nama_barang" => $_POST["nama_barang"], "jwt" => $_POST['jwt'], "aksi" => $_POST['aksi']);
    $abc->ubah_data($data);
    header('location:index.php?page=daftar-data');
} elseif ($_GET['aksi'] == 'hapus') {
    $data = array("id_barang" => $_GET['id_barang'], "jwt" => $_GET['jwt'], "aksi" => $_GET['aksi']);
    $abc->hapus_data($data);
    header('location:index.php?page=daftar-data');
} elseif ($_GET['aksi'] == 'logout') {
    setcookie('jwt', '', time() - 60 * 60);
    setcookie('id_pengguna', '', time() - 60 * 60);
    setcookie('nama', '', time() - 60 * 60);
    header('location:index.php?page=login');
}
unset($abc, $data, $data2);