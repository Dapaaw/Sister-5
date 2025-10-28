<?php
error_reporting(1);
include("Client.php");
$abc = new Client(); // buat object client

if (isset($_POST['aksi']) && $_POST['aksi'] == 'login') {
    $data = array(
        "id_pengguna" => $_POST['id_pengguna'],
        "pin" => $_POST['pin'],
        "aksi" => $_POST['aksi']
    );
    $data2 = $abc->login($data);

    if ($data2 && isset($data2->jwt)) {
        setcookie('jwt', $data2->jwt, time() + 3600);
        setcookie('id_pengguna', isset($data2->user->id_pengguna) ? $data2->user->id_pengguna : $_POST['id_pengguna'], time() + 3600);
        setcookie('nama', isset($data2->user->nama) ? $data2->user->nama : '', time() + 3600);
        header('Location: index.php?page=daftar-data');
        exit;
    } else {
        echo "<h3>Login gagal!</h3>";
        echo "<pre>";
        var_dump($data2);
        echo "</pre>";
        exit;
    }
} elseif (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
    $data = array(
        "id_barang" => $_POST['id_barang'],
        "nama_barang" => $_POST['nama_barang'],
        "jwt" => $_POST['jwt'],
        "aksi" => $_POST['aksi']
    );
    $abc->tambah_data($data);
    header('Location: index.php?page=daftar-data');
    exit;
} elseif (isset($_POST['aksi']) && $_POST['aksi'] == 'ubah') {
    $data = array(
        "id_barang" => $_POST["id_barang"],
        "nama_barang" => $_POST["nama_barang"],
        "jwt" => $_POST['jwt'],
        "aksi" => $_POST['aksi']
    );
    $abc->ubah_data($data);
    header('Location: index.php?page=daftar-data');
    exit;
} elseif (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $data = array(
        "id_barang" => $_GET['id_barang'],
        "jwt" => $_GET['jwt'],
        "aksi" => $_GET['aksi']
    );
    $abc->hapus_data($data);
    header('Location: index.php?page=daftar-data');
    exit;
} elseif (isset($_GET['aksi']) && $_GET['aksi'] == 'logout') {
    setcookie('jwt', '', time() - 3600);
    setcookie('id_pengguna', '', time() - 3600);
    setcookie('nama', '', time() - 3600);
    header('Location: index.php?page=login');
    exit;
}

unset($abc, $data, $data2);
