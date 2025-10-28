<?php
include "client.php";
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="css/bootstrap.css" rel="stylesheet">		
	<link href="css/bootstrap-responsive.css" rel="stylesheet">	
</head>
<body>
<div class="navbar">
  <div class="navbar-inner">
	<a class="brand" href="#">RPC - XML</a>
	<ul class="nav">
	  <li><a href="?page=home"><i class="icon-home"></i> Home</a></li>
	  <li><a href="?page=tambah"><i class="icon-plus-sign"></i> Tambah Data</a></li>
	  <li><a href="?page=daftar-data-server"><i class="icon-list"></i> Data Server</a></li>
	  <li><a href="?page=daftar-data-client"><i class="icon-list"></i> Data Client</a></li>
	</ul>
  </div>
</div>

<div class="container">
<fieldset>

<? if ($_GET['page']=='tambah') {
?>
<legend>Tambah Data</legend>	
	<div class="row-fluid ">
    <div class="span8 alert alert-info">
	<form class="form-horizontal" name="form1" method="POST" action="proses.php" novalidate>
		<input type="hidden" name="aksi" value="tambah"/>
		<div class="control-group">
			<label class="control-label" for="nim">NIM</label>
			<div class="controls">
				<input type="text" name="nim" class="input-small" placeholder="NIM"
					rel="tooltip" data-placement="right" title="Masukkan NIM"
					required data-validation-required-message="Harus diisi">				  
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="nama">Nama Mahasiswa</label>
			<div class="controls">
				<input type="text" name="nama" class="input-medium" placeholder="Nama Mahasiswa"
					rel="tooltip" data-placement="right" title="Masukkan Nama Mahasiswa"
					required data-validation-required-message="Harus diisi">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="no_hp">No. HP</label>
			<div class="controls">
				<input type="text" name="no_hp" class="input-medium" placeholder="No. HP">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="email">Email</label>
			<div class="controls">
				<input type="email" name="email" class="input-medium" placeholder="Email">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="alamat">Alamat</label>
			<div class="controls">
				<textarea name="alamat" class="input-xlarge" placeholder="Alamat"></textarea>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<button type="submit" name="simpan" class="btn btn-primary"><i class="icon-ok icon-white"></i> Simpan</button>
			</div>	
		</div>		
	</form>	
	</div>
	</div>
<? } elseif ($_GET['page']=='ubah') {	
	$r = $bb->tampil_mhs($_GET['nim']);	
?>
<legend>Ubah Data</legend>	
	<form name="form1" method="post" action="proses.php" class="form-horizontal">
		<input type="hidden" name="aksi" value="ubah"/>
		<input type="hidden" name="nim" value="<?=$r['nim']?>" />
		<div class="control-group">
			<label class="control-label" for="nim">NIM</label>
			<div class="controls">
				<input type="text" disabled class="input-small" placeholder="NIM" value="<?=$r['nim']?>">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="nama">Nama Mahasiswa</label>
			<div class="controls">
				<input type="text" name="nama" class="input-medium" placeholder="Nama Mahasiswa" value="<?=$r['nama']?>">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="no_hp">No. HP</label>
			<div class="controls">
				<input type="text" name="no_hp" class="input-medium" placeholder="No. HP" value="<?= isset($r['no_hp']) ? $r['no_hp'] : '' ?>">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="email">Email</label>
			<div class="controls">
				<input type="email" name="email" class="input-medium" placeholder="Email" value="<?= isset($r['email']) ? $r['email'] : '' ?>">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="alamat">Alamat</label>
			<div class="controls">
				<textarea name="alamat" class="input-xlarge" placeholder="Alamat"><?= isset($r['alamat']) ? $r['alamat'] : '' ?></textarea>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<button type="submit" name="ubah" class="btn btn-primary"><i class="icon-ok icon-white"></i> Ubah</button>
			</div>
		</div>
	</form>

<?  // menghapus variabel dari memory
	unset($r);	
	} else if ($_GET['page']=='daftar-data-server') {
?>
<legend>Daftar Data Server</legend>
	<form name="form1" method="post" action="proses.php" class="form-inline">
		<input type="hidden" name="aksi" value="sinkronisasi"/>
		<button type="submit" name="sinkronisasi" class="btn btn-primary" onclick="return confirm('Apakah Anda akan melakukan proses sinkronisasi data?')" ><i class="icon-ok icon-white"></i> Sinkronisasi Data</button>
	</form>

	<table class="table table-hover">
	<tr><th width='5%'>No</th>
		<th width='10%'>NIM</th>
		<th width='40%'>Nama</th>
		<th width='15%'>No. HP</th>
		<th width='15%'>Email</th>
		<th width='10%'>Alamat</th>
		<th width='5%'>Aksi</th>
	</tr>
	<?	$no = 1;
		$data_array = $bb->daftar_mhs_server();
		foreach ($data_array as $r)	{
	?>  <tr><td><?=$no?></td>
			<td><?=$r['nim']?></td>
			<td><?=$r['nama']?></td>
			<td><?= isset($r['no_hp']) ? $r['no_hp'] : '' ?></td>
			<td><?= isset($r['email']) ? $r['email'] : '' ?></td>
			<td><?= isset($r['alamat']) ? $r['alamat'] : '' ?></td>
			<td>
				<a href="?page=ubah&nim=<?=$r['nim']?>" role="button" class="btn btn-success"><i class="icon-pencil"></i></a>
				<a href="proses.php?aksi=hapus&nim=<?=$r['nim']?>" role="button" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" ><i class="icon-remove" ></i></a>
			</td>
		</tr>
	<?	$no++;
		}	

		// menghapus variabel dari memory
		unset($data_array,$r,$no);
	?>
	</table>

<? } else if ($_GET['page']=='daftar-data-client') { ?>
<legend>Daftar Data Client</legend>
	<table class="table table-hover">
	<tr><th width='5%'>No</th>
		<th width='10%'>NIM</th>
		<th width='40%'>Nama</th>
		<th width='15%'>No. HP</th>
		<th width='15%'>Email</th>
		<th width='15%'>Alamat</th>
	</tr>
	</tr>
	<?	$no = 1;
		$data_array = $bb->daftar_mhs_client();
		foreach ($data_array as $r)	{
	?>	<tr><td><?=$no?></td>
			<td><?=$r['nim']?></td>
			<td><?=$r['nama']?></td>
		<td><?= isset($r['no_hp']) ? $r['no_hp'] : '' ?></td>
		<td><?= isset($r['email']) ? $r['email'] : '' ?></td>
		<td><?= isset($r['alamat']) ? $r['alamat'] : '' ?></td>
	</tr>
	<?	$no++;
		}	
		
		// menghapus variabel dari memory
		unset($data_array,$r,$no);
	?>
	</table>
	
<? } else {
?>
<legend>Home</legend>
	Aplikasi sederhana ini menggunakan RPC (Remote Procedure Call) dengan format data XML (Extensible Markup Language).
</fieldset>
</div>
<?	}
?>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/tooltip.js"></script>

<!-- jqBootstrapValidation -->
<script src="js/jqBootstrapValidation.js"></script>
<script>
	$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
</script>

</body>
</html>
