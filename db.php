<?php
$servername = "localhost";
$kullanici_adi = "root";
$sifre = "";
$dbname = "berber_randevu";

// Bağlantıyı oluştur
$conn = new mysqli($servername, $kullanici_adi, $sifre, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı başarısız: " . $conn->connect_error);
}
?>