<?php
error_reporting(1);	// tampilkan error untuk debugging
header('Content-Type: text/xml; charset=UTF-8');

include "database.php";
// buat objek baru dari class Database
$abc = new Database();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$input = file_get_contents("php://input");
	$data = simplexml_load_string($input);
	$aksi = (string) $data->mahasiswa->aksi;
	$nim = (string) $data->mahasiswa->nim;
	$nama = (string) $data->mahasiswa->nama;
	$no_hp = (string) $data->mahasiswa->no_hp;
	$email = (string) $data->mahasiswa->email;
	$alamat = (string) $data->mahasiswa->alamat;

	if ($aksi == 'tambah') {
		$data2 = array(
			'nim' => $nim,
			'nama' => $nama,
			'no_hp' => $no_hp,
			'email' => $email,
			'alamat' => $alamat
		);
		$abc->tambah_data($data2);
	} elseif ($aksi == 'ubah') {
		$data2 = array(
			'nim' => $nim,
			'nama' => $nama,
			'no_hp' => $no_hp,
			'email' => $email,
			'alamat' => $alamat
		);
		$abc->ubah_data($data2);
	} elseif ($aksi == 'hapus') {
		$abc->hapus_data($nim);
	}

	unset($input, $data, $data2, $nim, $nama, $no_hp, $email, $alamat, $aksi, $abc);

} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
	// jika tampil data berdasarkan nim
	if (isset($_GET['aksi']) && $_GET['aksi'] == 'tampil' && isset($_GET['nim'])) {
		$nim = $abc->filter($_GET['nim']);
		$data = $abc->tampil_data($nim);

		$xml = "<uinmalang>";
		$xml .= "<mahasiswa>";
		$xml .= "<nim>" . $data['nim'] . "</nim>";
		$xml .= "<nama>" . $data['nama'] . "</nama>";
		$xml .= "<no_hp>" . $data['no_hp'] . "</no_hp>";
		$xml .= "<email>" . $data['email'] . "</email>";
		$xml .= "<alamat>" . $data['alamat'] . "</alamat>";
		$xml .= "</mahasiswa>";
		$xml .= "</uinmalang>";

		echo $xml;
	} else // tampil semua data
	{
		$data = $abc->tampil_semua_data();
		$xml = "<uinmalang>";
		foreach ($data as $a) {
			$xml .= "<mahasiswa>";
			foreach ($a as $kolom => $value) {
				$xml .= "<$kolom>$value</$kolom>";
				// atau jika ingin aman dari karakter spesial:
				// $xml .= "<$kolom><![CDATA[$value]]></$kolom>";
			}
			$xml .= "</mahasiswa>";
		}
		$xml .= "</uinmalang>";
		echo $xml;
	}
	unset($nim, $data, $xml);
}
?>