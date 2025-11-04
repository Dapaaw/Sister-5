<?php
error_reporting(1);

include_once 'core.php';
include_once 'lib/php-jwt/src/BeforeValidException.php';
include_once 'lib/php-jwt/src/ExpiredException.php';
include_once 'lib/php-jwt/src/SignatureInvalidException.php';
include_once 'lib/php-jwt/src/JWT.php';
use \Firebase\JWT\JWT;

include_once "database.php";
$abc = new Database();

if (isset($_SERVER['HTTP_ORIGIN'])) {
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  header("Content-Type: application/json; charset=UTF-8");
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Max-Age: 3600');  
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
      header("Access-Control-Allow-Methods: GET,POST,OPTIONS"); 
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
      header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
  exit(0);
}

$postdata = file_get_contents("php://input");
$data = json_decode($postdata);

// === LOGIN (generate JWT) ===
if ($_SERVER['REQUEST_METHOD']=='POST' && $data->aksi=='login' && isset($data->id_pengguna) && isset($data->pin)) {
  $data2['id_pengguna'] = $data->id_pengguna;
  $data2['pin'] = $data->pin;
  
  // cek login pengguna
  $data3 = $abc->login($data2);
  if ($data3) {    
      // generate JSON Web Token (JWT)
      $token = array(
         "iat" => $issued_at,
         "exp" => $expiration_time,
         "iss" => $issuer,
         "data" => array(
             "id_pengguna" => $data3['id_pengguna'],
             "nama" => $data3['nama']        
         )
      ); 

      // generate jwt
      $jwt = JWT::encode($token, $key);

      // respon sukses
      http_response_code(200); 
      echo json_encode(array(
          "pesan" => "Login sukses",
          "id_pengguna" => $data3['id_pengguna'],
          "nama" => $data3['nama'],
          "jwt" => $jwt
      ));
  } else {
      http_response_code(401); 
      echo json_encode(array("pesan" => "Login gagal"));
  }

// === AKSI POST (tambah, ubah, hapus) ===
} elseif ($_SERVER['REQUEST_METHOD']=='POST') {
    $jwt = $data->jwt; 
    $aksi = $data->aksi; 
    $nim = $data->nim;
    $nama = $data->nama;
    $no_hp = $data->no_hp;
    $email = $data->email;
    $alamat = $data->alamat;
    
    try { 
        JWT::decode($jwt, $key, array('HS256')); 

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
            $data2 = array('nim' => $nim, 'aksi' => 'hapus');
        } 

        http_response_code(200); 
        echo json_encode(array(
          "pesan" => "Aksi $aksi berhasil",
          "data" => $data2
        ));

    } catch (Exception $e) {
        http_response_code(401);   
        echo json_encode(array("pesan" => "Token tidak valid atau kadaluarsa"));
    }

} elseif ($_SERVER['REQUEST_METHOD']=='GET') {
    $jwt = $_GET['jwt']; 

    try {
        JWT::decode($jwt, $key, array('HS256')); 
          
        if (isset($_GET['aksi']) && $_GET['aksi']=='tampil' && isset($_GET['nim'])) {
            $nim = $abc->filter($_GET['nim']);  
            $data = $abc->tampil_data($nim);
        } else {
            $data = $abc->tampil_semua_data();
        }

        http_response_code(200); 
        echo json_encode($data); 

    } catch (Exception $e) {
        http_response_code(401);   
        echo json_encode(array("pesan" => "Token tidak valid atau dilarang akses"));
    }

// === Jika tanpa JWT atau request salah ===
} else {
    http_response_code(401); 
    echo json_encode(array("pesan" => "Dilarang akses tanpa token JWT"));
}

unset($abc,$postdata,$data,$data2,$data3,$token,$key,$issued_at,$expiration_time,$issuer,$jwt,$nim,$nama,$aksi,$no_hp,$email,$alamat,$e); 
?>
