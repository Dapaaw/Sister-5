<?php
class Database
{
    private $host = "localhost";
    private $dbname = "rss_feed";
    private $user = "root";
    private $password = "";
    private $port = "3306";
    private $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8", $this->user, $this->password);
        } catch (PDOException $e) {
            echo "Koneksi gagal";
        }
    }

    public function daftar_berita()
    {
        $query = $this->conn->prepare("SELECT judul,deskripsi,link,tanggal FROM berita ORDER BY tanggal DESC");
        $query->execute();

        $data = $query->fetchAll(PDO::FETCH_ASSOC);

        return $data;

        $query->closeCursor();
        unset($data);
    }
}
?>