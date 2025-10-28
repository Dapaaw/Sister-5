<?php
include_once 'database.php';

class RSSFeed
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
        unset($db);
    }

    public function generateFeed()
    {
        header("Content-Type: application/rss+xml; charset=UTF-8");
        $rssFeed = "<?xml version='1.0' encoding='UTF-8' ?>";
        $rssFeed .= "<rss version='2.0'>";
        $rssFeed .= "<channel>";
        $rssFeed .= "<judul>RSS Feed</judul>";
        $rssFeed .= "<link>http://192.168.56.2</link>";
        $rssFeed .= "<deskripsi>Ini adalah contoh RSS feed</deskripsi>";
        $rssFeed .= "<language>en-us</language>";
        $data = $this->db->daftar_berita();
        for ($i = 0; $i < count($data); $i++) {
            $rssFeed .= "<item>";
            $rssFeed .= "<judul>" . htmlspecialchars($data[$i]['judul']) . "</judul>";
            $rssFeed .= "<deskripsi>" . htmlspecialchars($data[$i]['deskripsi']) . "</deskripsi>";
            $rssFeed .= "<link>" . htmlspecialchars($data[$i]['link']) . "</link>";
            $rssFeed .= "<tanggal>" . date("D, d M Y H:i:s", strtotime($data[$i]['tanggal'])) . "</tanggal>";
            $rssFeed .= "</item>";
        }
        $rssFeed .= "</channel>";
        $rssFeed .= "</rss>";

        echo $rssFeed;
        unset($rssFeed, $data, $i);
    }
}

$db = new Database();
$rss = new RSSFeed($db);
$rss->generateFeed();
?>