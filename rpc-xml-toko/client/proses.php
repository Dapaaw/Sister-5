<?php
    include("RPCClient.php");

    if ($_POST["aksi"] == "tambah") {
        $data = xmlrpc_encode_request("method", array("aksi" => $_POST['aksi'], "id_barang" => $_POST['id_barang'], "nama_barang" => $_POST['nama_barang']));
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
        $data = xmlrpc_encode_request('method', array('aksi' => $_POST['aksi'], 'id_barang' => $_POST['id_barang'], 'nama_barang' => $_POST['nama_barang']));
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
        $data = xmlrpc_encode_request('method', array('aksi' => $_GET['aksi'], 'id_barang' => $_GET['id_barang'],));
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
