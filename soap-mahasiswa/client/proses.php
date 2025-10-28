<?php
// proses.php — diperbarui supaya cocok dengan client.php yang membuat $objek
include "client.php";

// Pastikan $objek ada
if (!isset($objek)) {
    die("Client SOAP belum diinisialisasi. Periksa client.php");
}

// Ambil aksi dengan aman (dari GET atau POST)
$aksi = isset($_REQUEST['aksi']) ? $_REQUEST['aksi'] : '';

if ($aksi === 'tambah') {
    // Ambil data dari form (POST)
    $nim   = isset($_POST['nim']) ? trim($_POST['nim']) : '';
    $nama  = isset($_POST['nama']) ? trim($_POST['nama']) : '';
    $no_hp = isset($_POST['no_hp']) ? trim($_POST['no_hp']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $alamat= isset($_POST['alamat']) ? trim($_POST['alamat']) : '';

    // Siapkan array sesuai metode tambah_data($data) di client.php
    $data = [
        'nim'   => $nim,
        'nama'  => $nama,
        'no_hp' => $no_hp,
        'email' => $email,
        'alamat'=> $alamat
    ];

    // Panggil method client (sesuai client.php sebelumnya)
    $objek->tambah_data($data);

    header("Location: index.php?page=daftar-server");
    exit;

} elseif ($aksi === 'hapus') {
    $nim = isset($_GET['nim']) ? trim($_GET['nim']) : '';
    if ($nim !== '') {
        $objek->hapus_data($nim);
    }
    header("Location: index.php?page=daftar-server");
    exit;

} elseif ($aksi === 'ubah' || $aksi === 'update') {
    // dari form ubah: aksi = 'ubah'
    $nim   = isset($_POST['nim']) ? trim($_POST['nim']) : '';
    $nama  = isset($_POST['nama']) ? trim($_POST['nama']) : '';
    $no_hp = isset($_POST['no_hp']) ? trim($_POST['no_hp']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $alamat= isset($_POST['alamat']) ? trim($_POST['alamat']) : '';

    $data = [
        'nim'   => $nim,
        'nama'  => $nama,
        'no_hp' => $no_hp,
        'email' => $email,
        'alamat'=> $alamat
    ];

    $objek->ubah_data($data);

    header("Location: index.php?page=daftar-server");
    exit;

} elseif ($aksi === 'sinkronisasi') {
    // memanggil sinkronisasi yang ada di client.php
    $objek->sinkronisasi();
    header("Location: index.php?page=daftar-client");
    exit;

} else {
    // aksi tidak dikenali
    header("Location: index.php");
    exit;
}
?>
