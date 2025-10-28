<?php
error_reporting(1);
header('Content-Type: text/xml; charset=UTF-8');
include "database.php";

$aa = new database(); // objek database

// fungsi filter
function filter($data) {
    $data = preg_replace('/[^a-zA-Z0-9]/', '', $data);
    return $data;
}

// fungsi menampilkan semua data
function tampil_semua_data() {
    $db = new database();
    return $db->tampil_semua_data();
}

// fungsi menambah data mahasiswa
function tambah_data($nim, $nama, $no_hp, $email, $alamat) {
    $db = new database();
    $data = [
        'nim' => $nim,
        'nama' => $nama,
        'no_hp' => $no_hp,
        'email' => $email,
        'alamat' => $alamat
    ];
    $db->tambah_data($data);
    return "Data berhasil ditambahkan";
}

// fungsi mengedit data mahasiswa
function edit_data($nim, $nama, $no_hp, $email, $alamat) {
    $db = new database();
    $data = [
        'nim' => $nim,
        'nama' => $nama,
        'no_hp' => $no_hp,
        'email' => $email,
        'alamat' => $alamat
    ];
    $db->ubah_data($data);
    return "Data berhasil diubah";
}

// fungsi menghapus data
function hapus_data($nim) {
    $db = new database();
    $db->hapus_data($nim);
    return "Data berhasil dihapus";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = file_get_contents("php://input");
    $data = xmlrpc_decode($input);

    $aksi = $data[0]['aksi'];

    if ($aksi == 'tambah') {
        tambah_data(
            $data[0]['nim'],
            $data[0]['nama'],
            $data[0]['no_hp'],
            $data[0]['email'],
            $data[0]['alamat']
        );
    } elseif ($aksi == 'ubah') {
        edit_data(
            $data[0]['nim'],
            $data[0]['nama'],
            $data[0]['no_hp'],
            $data[0]['email'],
            $data[0]['alamat']
        );
    } elseif ($aksi == 'hapus') {
        hapus_data($data[0]['nim']);
    }

    unset($input, $data);

} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (($_GET['aksi'] == 'tampil') && isset($_GET['nim'])) {
        $nim = filter($_GET['nim']);
        $data = $aa->tampil_data($nim);
    } else {
        $data = $aa->tampil_semua_data();
    }
    echo xmlrpc_encode($data);
    unset($data);
}
?>
