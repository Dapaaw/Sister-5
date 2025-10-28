<?php
// tampilkan semua error kecuali notice biar lebih bersih
error_reporting(E_ALL & ~E_NOTICE);

include("Client.php");
$abc = new Client(); // inisialisasi class client
?>
<!doctype html>
<html>

<head>
    <title>Aplikasi Toko - JWT Client</title>
</head>

<body>

    <?php if (isset($_COOKIE['jwt'])) { ?>
        <!-- Menu jika sudah login -->
        <a href="?page=home">Home</a> |
        <a href="?page=tambah">Tambah Data</a> |
        <a href="?page=daftar-data">Daftar Server</a> |
        <a href="proses.php?aksi=logout" onclick="return confirm('Apakah Anda Ingin Logout?')">Logout</a>
        <br>
        <?php echo '<strong>' . $_COOKIE['nama'] . ' (' . $_COOKIE['id_pengguna'] . ')</strong>'; ?>
    <?php } else { ?>
        <!-- Menu jika belum login -->
        <a href="?page=home">Home</a> |
        <a href="?page=login">Login</a>
    <?php } ?>

    <br><br />
    <fieldset>
        <?php
        // ambil parameter ?page (default = home)
        $page = isset($_GET['page']) ? $_GET['page'] : 'home';

        if ($page === 'login' && !isset($_COOKIE['jwt'])) { ?>
            <!-- Form Login -->
            <legend>Login</legend>
            <form name="form" action="proses.php" method="post">
                <input type="hidden" name="aksi" value="login" />
                <label>Id Pengguna</label>
                <input type="text" name="id_pengguna" />
                <br />
                <label>Pin</label>
                <input type="password" name="pin" />
                <br />
                <button type="submit" name="login">Login</button>
            </form>

        <?php } elseif ($page === 'tambah' && isset($_COOKIE['jwt'])) { ?>
            <!-- Form Tambah Data -->
            <legend>Tambah Data</legend>
            <form name="form" action="proses.php" method="post">
                <input type="hidden" name="aksi" value="tambah" />
                <input type="hidden" name="jwt" value="<?= $_COOKIE['jwt'] ?>" />
                <label>Id Barang</label>
                <input type="text" name="id_barang" />
                <br />
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" />
                <br />
                <button type="submit" name="simpan">Simpan</button>
            </form>

        <?php } elseif ($page === 'ubah' && isset($_COOKIE['jwt'])) {
            $data = ['jwt' => $_COOKIE['jwt'], 'id_barang' => $_GET['id_barang']];
            $r = $abc->tampil_data($data);
        ?>
            <!-- Form Ubah Data -->
            <legend>Ubah Data</legend>
            <form name="form" method="post" action="proses.php">
                <input type="hidden" name="aksi" value="ubah" />
                <input type="hidden" name="jwt" value="<?= $_COOKIE['jwt'] ?>" />
                <input type="hidden" name="id_barang" value="<?= $r->id_barang ?>" />
                <label>ID Barang</label>
                <input type="text" value="<?= $r->id_barang ?>" disabled>
                <br />
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" value="<?= $r->nama_barang ?>">
                <br />
                <button type="submit" name="ubah">Ubah</button>
            </form>

        <?php } elseif ($page === 'daftar-data' && isset($_COOKIE['jwt'])) { ?>
            <!-- Tabel Daftar Data -->
            <legend>Daftar Data Server</legend>
            <table border="1">
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">ID Barang</th>
                    <th width="75%">Nama</th>
                    <th width="5%" colspan="2">Aksi</th>
                </tr>
                <?php
                $no = 1;
                $data_array = $abc->tampil_semua_data($_COOKIE['jwt']);
                foreach ($data_array as $r) {
                ?>
                    <tr>
                        <td><?= $no ?></td>
                        <td><?= $r->id_barang ?></td>
                        <td><?= $r->nama_barang ?></td>
                        <td><a href="?page=ubah&id_barang=<?= $r->id_barang ?>">Ubah</a></td>
                        <td><a href="proses.php?aksi=hapus&id_barang=<?= $r->id_barang ?>&jwt=<?= $_COOKIE['jwt'] ?>"
                                onclick="return confirm('Apakah Anda ingin menghapus data ini?')">Hapus</a></td>
                    </tr>
                <?php $no++;
                }
                unset($data_array, $r, $no);
                ?>
            </table>

        <?php } else { ?>
            <!-- Halaman Home -->
            <legend>Home</legend>
            Aplikasi sederhana ini menggunakan RESTful JSON Web Token (JWT).
        <?php } ?>
    </fieldset>
</body>

</html>