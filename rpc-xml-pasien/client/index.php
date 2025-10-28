<?php
error_reporting(1);
include("client.php");
?>
<!doctype html>
<html>

<head>
    <title></title>
</head>

<body>
    <a href="?page=home">Home</a>|<a href="?page=tambah">Tambah Data</a>|<a href="?page=daftar-data">Daftar Server</a>
    <br><br />
    <? if ($_GET['page'] == 'tambah') { ?>
        <legend>Tambah Data</legend>
        <form name="form" action="proses.php" method="post">
            <input type="hidden" name="aksi" value="tambah" />
            <label>Id Pasien</label>
            <input type="text" name="id_pasien" />
            <br />
            <label>Nama Pasien</label>
            <input type="text" name="nama_pasien" />
            <br />
			<label>Alamat</label>
			<input type="text" name="alamat" />
			<br />
			<label>Nomor Rekam Medis</label>
			<input type="text" name="nomor_rekam_medis" />
			<br />

            <button type="submit" name="simpan">Simpan</button>
        </form>
    <? } elseif ($_GET['page'] == 'ubah') {
        $r = $abc->tampil_data($_GET['id_pasien']);
    ?>
        <legend>Ubah Data</legend>
        <form name="form" method="post" action="proses.php">
            <input type="hidden" name="aksi" value="ubah" />
            <input type="hidden" name="id_pasien" value="<?= $r['id_pasien'] ?>" />
            <label>ID Pasien</label>
            <input type="text" value="<?= $r['id_pasien'] ?>" disabled>
            <br />
            <label>Nama Pasien</label>
            <input type="text" name="nama_pasien" value="<?= $r['nama_pasien'] ?>">
            <br />
			<label>Alamat</label>
			<input type="text" name="alamat" value="<?= $r['alamat'] ?>">
			<br />
			<label>Nomor Rekam Medis</label>
			<input type="text" name="nomor_rekam_medis" value="<?= $r['nomor_rekam_medis'] ?>">
			<br />
            <button type="submit" name="ubah">Ubah</button>
        </form>
    <? unset($r);
    } elseif ($_GET['page'] == 'daftar-data') {
    ?>
        <legend>Daftar Data Server</legend>
        <table border="1">
            <tr>
                <th width="5%">No</th>
                <th>ID Pasien</th>
				<th>Nama Pasien</th>
				<th>alamat</th>
				<th>nomor rekam medis</th>
                <th width="5%" colspan="2">Aksi</th>
            </tr>
            <? $no = 1;
            $data_array = $abc->tampil_semua_data();
            foreach ($data_array as $r) {
            ?>
                <tr>
                    <td><?= $no ?></td>
                    <td><?= $r['id_pasien'] ?></td>
                    <td><?= $r['nama_pasien'] ?></td>
					<td><?= $r['alamat'] ?></td>
					<td><?= $r['nomor_rekam_medis'] ?></td>
                    <td><a href="?page=ubah&id_pasien=<?= $r['id_pasien'] ?>">Ubah</a></td>
                    <td><a href="proses.php?aksi=hapus&id_pasien=<?= $r['id_pasien'] ?>"
                            onclick="return confirm('Apakah Anda ingin menghapus data ini?')">Hapus</a></td>
                </tr>
            <? $no++;
            }
            unset($data_array, $r, $no);
            ?>
        </table>
    <? } else { ?>
        <legend>Home</legend>
        Aplikasi sederhana ini menggunakan RPC
        </fieldset>
    <? } ?>
</body>

</html>