<?php
include "client.php";
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

?>
<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>SOAP Mahasiswa</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/bootstrap-responsive.css" rel="stylesheet">
</head>

<body>
	<div class="navbar">
		<div class="navbar-inner">
			<a class="brand" href="#">SOAP</a>
			<ul class="nav">
				<li><a href="?page=home"><i class="icon-home"></i> Home</a></li>
				<li><a href="?page=tambah"><i class="icon-plus-sign"></i> Tambah Data</a></li>
				<li><a href="?page=daftar-server"><i class="icon-list"></i> Data Server</a></li>
				<li><a href="?page=daftar-client"><i class="icon-list"></i> Data Client</a></li>
			</ul>
		</div>
	</div>

	<div class="container">
		<fieldset>
			<?php if ($page == 'tambah') { ?>
				<legend>Tambah Data</legend>
				<div class="row-fluid">
					<div class="span8 alert alert-info">
						<form class="form-horizontal" name="form1" method="POST" action="proses.php" novalidate>
							<input type="hidden" name="aksi" value="tambah" />

							<div class="control-group">
								<label class="control-label" for="nim">NIM</label>
								<div class="controls">
									<input type="text" name="nim" class="input-small" placeholder="NIM" required>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="nama">Nama Mahasiswa</label>
								<div class="controls">
									<input type="text" name="nama" class="input-medium" placeholder="Nama Mahasiswa"
										required>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="no_hp">No HP</label>
								<div class="controls">
									<input type="text" name="no_hp" class="input-medium" placeholder="Nomor HP" required>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="email">Email</label>
								<div class="controls">
									<input type="email" name="email" class="input-medium" placeholder="Email" required>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="alamat">Alamat</label>
								<div class="controls">
									<textarea name="alamat" class="input-large" placeholder="Alamat Lengkap"
										required></textarea>
								</div>
							</div>

							<div class="control-group">
								<div class="controls">
									<button type="submit" name="simpan" class="btn btn-primary">
										<i class="icon-ok icon-white"></i> Simpan
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>

			<?php } elseif ($page == 'ubah') {
				$r = $objek->tampil_data($_GET['nim']);
				?>
				<legend>Ubah Data</legend>
				<form name="form1" method="post" action="proses.php" class="form-horizontal">
					<input type="hidden" name="aksi" value="ubah" />
					<input type="hidden" name="nim" value="<?= $r['nim'] ?>" />

					<div class="control-group">
						<label class="control-label">NIM</label>
						<div class="controls">
							<input type="text" disabled class="input-small" value="<?= $r['nim'] ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Nama</label>
						<div class="controls">
							<input type="text" name="nama" class="input-medium" value="<?= $r['nama'] ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">No HP</label>
						<div class="controls">
							<input type="text" name="no_hp" class="input-medium" value="<?= $r['no_hp'] ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Email</label>
						<div class="controls">
							<input type="email" name="email" class="input-medium" value="<?= $r['email'] ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Alamat</label>
						<div class="controls">
							<textarea name="alamat" class="input-large"><?= $r['alamat'] ?></textarea>
						</div>
					</div>

					<div class="control-group">
						<div class="controls">
							<button type="submit" name="ubah" class="btn btn-primary">
								<i class="icon-ok icon-white"></i> Ubah
							</button>
						</div>
					</div>
				</form>
				<?php unset($r); ?>

			<?php } elseif ($page == 'daftar-server') { ?>
				<legend>Daftar Data Server</legend>
				<form name="form1" method="post" action="proses.php" class="form-inline">
					<input type="hidden" name="aksi" value="sinkronisasi" />
					<button type="submit" name="sinkronisasi" class="btn btn-primary"
						onclick="return confirm('Apakah Anda akan melakukan proses sinkronisasi data?')">
						<i class="icon-ok icon-white"></i> Sinkronisasi Data
					</button>
				</form>

				<table class="table table-hover">
					<tr>
						<th>No</th>
						<th>NIM</th>
						<th>Nama</th>
						<th>No HP</th>
						<th>Email</th>
						<th>Alamat</th>
						<th>Ubah</th>
						<th>Hapus</th>
					</tr>
					<?php
					$no = 1;
					$data_array = $objek->tampil_semua_data();
					foreach ($data_array as $r) {
						?>
						<tr>
							<td><?= $no ?></td>
							<td><?= $r['nim'] ?></td>
							<td><?= $r['nama'] ?></td>
							<td><?= $r['no_hp'] ?></td>
							<td><?= $r['email'] ?></td>
							<td><?= $r['alamat'] ?></td>
							<td><a href="?page=ubah&nim=<?= $r['nim'] ?>" class="btn btn-success"><i
										class="icon-pencil"></i></a></td>
							<td><a href="proses.php?aksi=hapus&nim=<?= $r['nim'] ?>" class="btn btn-danger"
									onclick="return confirm('Yakin hapus data ini?')"><i class="icon-remove"></i></a></td>
						</tr>
						<?php $no++;
					}
					unset($data_array, $r, $no);
					?>
				</table>

			<?php } elseif ($page == 'daftar-client') { ?>
				<legend>Daftar Data Client</legend>
				<table class="table table-hover">
					<tr>
						<th>No</th>
						<th>NIM</th>
						<th>Nama</th>
						<th>No HP</th>
						<th>Email</th>
						<th>Alamat</th>
					</tr>
					<?php
					$no = 1;
					$data_array = $objek->daftar_mhs_client();
					foreach ($data_array as $r) {
						?>
						<tr>
							<td><?= $no ?></td>
							<td><?= $r['nim'] ?></td>
							<td><?= $r['nama'] ?></td>
							<td><?= $r['no_hp'] ?></td>
							<td><?= $r['email'] ?></td>
							<td><?= $r['alamat'] ?></td>
						</tr>
						<?php $no++;
					}
					unset($data_array, $r, $no);
					?>
				</table>

			<?php } else { ?>
				<legend>Home</legend>
				<p>
					Aplikasi sederhana ini menggunakan web service SOAP (Simple Object Access Protocol)
					dengan format data XML (Extensible Markup Language).
				</p>
			<?php } ?>
		</fieldset>
	</div>

	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/tooltip.js"></script>
	<script src="js/jqBootstrapValidation.js"></script>
	<script>
		$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); });
	</script>
</body>

</html>