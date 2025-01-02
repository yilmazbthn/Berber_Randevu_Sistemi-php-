<?php
require 'db.php';
session_start();

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit();
}

$kullanici_id = $_SESSION['kullanici_id'];

$query = "SELECT r.*, k.Ad_Soyad as berber_adi, h.hizmet_adi, h.fiyat 
          FROM randevular r 
          JOIN kullanicilar k ON r.berber_id = k.id 
          JOIN hizmetler h ON r.hizmet_id = h.id 
          WHERE r.kullanici_id = ? 
          ORDER BY r.randevu_zamani DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $kullanici_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Randevularım</title>
    <link href="css/main.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="images/fav.png"/>
</head>
<style>
    body {
        background-image: url('images/4.jpg');
        background-size: cover;
        margin: 0;
        font-family: 'Poppins', sans-serif;
    }
</style>
<body>
    <div class="container">
        <h2>Randevularım</h2>
        <table>
            <thead>
                <tr>
                    <th>Berber</th>
                    <th>Zaman</th>
                    <th>Hizmet</th>
                    <th>Fiyat</th>
                    <th>Durum</th>
                    <th>İptal</th>
                    <th>Düzenle</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['berber_adi']) ?></td>
                        <td><?= htmlspecialchars($row['randevu_zamani']) ?></td>
                        <td><?= htmlspecialchars($row['hizmet_adi']) ?></td>
                        <td><?= htmlspecialchars($row['fiyat']) ?> TL</td>
                        <td><?= htmlspecialchars($row['durum']) ?></td>
                        <td>
                            <a href="iptal_randevu.php?id=<?= $row['id'] ?>" class="btn">İptal Et</a>
                        </td>
                        <td>
                            <a href="duzenle_randevu.php?id=<?= $row['id'] ?>" class="btn">Düzenle</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="table-actions">
            <a href="index.php" class="btn">Geri</a>
        </div>
    </div>
</body>
</html>
