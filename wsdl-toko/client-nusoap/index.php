<?php
include "Client.php";
?>
<!doctype html>
<html>

<head>
    <title></title>
</head>

<body>
    <p>
        <a href="?page=home">Home</a> |
        <a href="?page=tambah">Tambah Data</a> |
        <a href="?page=daftar-data">Daftar Data Server</a>
    </p>
    <hr>

    <?php if (isset($_GET['page']) && $_GET['page'] == 'tambah') { ?>
        <fieldset>
            <legend>Tambah Data</legend>
            <form name="form" method="POST" action="proses.php">
                <input type="hidden" name="aksi" value="tambah" />
                <label>ID Barang</label>
                <input type="text" name="id_barang" />
                <br />
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" />
                <br />
                <button type="submit" name="simpan">Simpan</button>
            </form>
        </fieldset>
    <?php } elseif (isset($_GET['page']) && $_GET['page'] == 'ubah') {
        $r = $abc->tampil_data($_GET['id_barang']);
    ?>
        <legend>Ubah Data</legend>
        <form name="form" method="POST" action="proses.php">
            <input type="hidden" name="aksi" value="ubah" />
            <input type="hidden" name="id_barang" value="<?php echo $_GET['id_barang']; ?>" />
            <label>ID Barang</label>
            <input type="text" value="<?php echo $r['id_barang']; ?>" disabled />
            <br />
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" value="<?php echo $r['nama_barang']; ?>" />
            <br />
            <button type="submit" name="ubah">Ubah</button>
        </form>
    <?php } elseif (isset($_GET['page']) && $_GET['page'] == 'daftar-data') { ?>
        <fieldset>
            <legend>Daftar Data Server</legend>
            <table border="1">
                <tr>
                    <th width="10%">ID Barang</th>
                    <th width="75%">Nama Barang</th>
                    <th width="15%" colspan="2">Aksi</th>
                </tr>
                <?php
                $data_array = $abc->tampil_semua_data();
                foreach ($data_array as $r) {
                ?>
                    <tr>
                        <td><?php echo $r['id_barang']; ?></td>
                        <td><?php echo $r['nama_barang']; ?></td>
                        <td><a href="?page=ubah&id_barang=<?php echo $r['id_barang']; ?>">Ubah</a></td>
                        <td><a href="proses.php?aksi=hapus&id_barang=<?php echo $r['id_barang']; ?>"
                                onclick="return confirm('Apakah Anda ingin menghapus data ini?')">Hapus</a></td>
                    </tr>
                <?php } ?>
            </table>
            <?php unset($data_array, $r); ?>
        </fieldset>
    <?php } else { ?>
        <legend>Home</legend>
        Aplikasi sederhana ini menggunakan WSDL (Web Services Description Language) dengan format data XML (Extensible Markup Language). WSDL Server dan Client menggunakan library nuSOAP.
    <?php } ?>
</body>

</html>