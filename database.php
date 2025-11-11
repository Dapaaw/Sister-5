<?php
class database
{
    private $host = "localhost";
    private $dbname = "serviceserver";
    private $conn;

    // koneksi ke database mysql
    private $driver = "mysql";
    private $user = "root";
    private $password = "";
    private $port = "3306";

    public function __construct()
    {
        try {
            if ($this->driver == 'mysql') {
                $this->conn = new PDO(
                    "mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8",
                    $this->user,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } elseif ($this->driver == 'pgsql') {
                $this->conn = new PDO(
                    "pgsql:host=$this->host;port=$this->port;dbname=$this->dbname;user=$this->user;password=$this->password"
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        } catch (PDOException $e) {
            echo "Koneksi gagal: " . $e->getMessage();
        }
    }

    public function tampil_semua_data()
    {
        $query = $this->conn->prepare("SELECT nim, nama, no_hp, email, alamat FROM mahasiswa ORDER BY nim");
        $query->execute();
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $data;
    }

    public function tampil_data($nim)
    {
        $query = $this->conn->prepare("SELECT nim, nama, no_hp, email, alamat FROM mahasiswa WHERE nim=?");
        $query->execute([$nim]);
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $data;
    }

    public function tambah_data($data)
    {
        $query = $this->conn->prepare("INSERT INTO mahasiswa (nim, nama, no_hp, email, alamat) VALUES (?,?,?,?,?)");
        $query->execute([
            $data['nim'],
            $data['nama'],
            $data['no_hp'],
            $data['email'],
            $data['alamat']
        ]);
        $query->closeCursor();
    }

    public function ubah_data($data)
    {
        $query = $this->conn->prepare("UPDATE mahasiswa SET nama=?, no_hp=?, email=?, alamat=? WHERE nim=?");
        $query->execute([
            $data['nama'],
            $data['no_hp'],
            $data['email'],
            $data['alamat'],
            $data['nim']
        ]);
        $query->closeCursor();
    }

    public function hapus_data($nim)
    {
        $query = $this->conn->prepare("DELETE FROM mahasiswa WHERE nim=?");
        $query->execute([$nim]);
        $query->closeCursor();
    }
}
?>
