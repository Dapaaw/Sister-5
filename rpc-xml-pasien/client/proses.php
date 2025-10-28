<?php
include "client.php";


    if ($_POST["aksi"] == "tambah") {
        $data = xmlrpc_encode_request("method", array("aksi" => $_POST['aksi'], "id_pasien" => $_POST['id_pasien'], "nama_pasien" => $_POST['nama_pasien'], "alamat" => $_POST['alamat'], "nomor_rekam_medis" => $_POST['nomor_rekam_medis']));
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type:text/xml;charset=UTF-8',
                'content' => $data
            )
        ));
        $file = file_get_contents($url, false, $context);
        xmlrpc_decode($file);
        header('location:index.php?page=daftar-data');

        unset($data, $context, $url, $response);
    } elseif ($_POST['aksi'] == 'ubah') {
        $data = xmlrpc_encode_request('method', array('aksi' => $_POST['aksi'], 'id_pasien' => $_POST['id_pasien'], 'nama_pasien' => $_POST['nama_pasien'], 'alamat' => $_POST['alamat'], 'nomor_rekam_medis' => $_POST['nomor_rekam_medis']));
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type:text/xml;charset=UTF-8',
                'content' => $data
            )
        ));
        $file = file_get_contents($url, false, $context);
        header('location:index.php?page=daftar-data');

        unset($data, $context, $url, $response);
    } elseif ($_GET['aksi'] == 'hapus') {
        $data = xmlrpc_encode_request('method', array('aksi' => $_GET['aksi'], 'id_pasien' => $_GET['id_pasien'],));
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type:text/xml;charset=UTF-8',
                'content' => $data
            )
        ));
        $file = file_get_contents($url, false, $context);
        xmlrpc_decode($file);
        header('location:index.php?page=daftar-data');
        unset($data, $context, $url);
    }
