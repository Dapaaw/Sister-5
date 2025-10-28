<?php
class RSSClient {
    private $url;

    public function __construct($url) {
        $this->url = $url;
        unset($url);
    }

    public function fetchFeed() {
        $feed = simplexml_load_file($this->url);
        $berita = [];
        foreach ($feed->channel->item as $item) {
            $berita[] = [
                'judul' => (string) $item->judul,
                'link' => (string) $item->link,
                'deskripsi' => (string) $item->deskripsi,
                'tanggal' => (string) $item->tanggal
            ];
        }
        return $berita;
        unset($feed, $berita);
    }

    public function displayFeed() {
        $beritaItems = $this->fetchFeed();
        foreach ($beritaItems as $berita) {
            echo "<h3><a href='{$berita['link']}'>{$berita['judul']}</a></h3>";
            echo "<p>{$berita['deskripsi']}</p>";
            echo "<small><em>{$berita['tanggal']}</em></small><hr>";
        }
        unset($beritaItems, $berita);
    }

    public function __destruct() {
        unset($this->url);
    }
}

$url = "http://192.168.56.2/rss/server/server.php";
$abc = new RSSClient($url);
$abc->displayFeed();
?>