<?php
require 'db.php';
session_start();

if (!isset($_SESSION['kullanici_id']) || $_SESSION['rol'] != 'berber') {
    header("Location: giris.php");
    exit();
}

$berber_id = $_SESSION['kullanici_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $randevu_id = $_POST['randevu_id'];
    $durum = $_POST['durum'];

    $sql = "UPDATE randevular SET durum = ? WHERE id = ? AND berber_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $durum, $randevu_id, $berber_id);
    
    if ($stmt->execute()) {
        echo "
            <script>
                alert('Randevu Güncellendi!');
               );
            </script>
        ";
    } else {
        echo "Güncelleme başarısız: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yönetim Paneli</title>
    <link href="css/main.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="images/fav.png"/>
</head>
<style>
    body {
    background-image: url('images/5.jpg');  /* Arka plan resmi yolu */
    background-size: cover;  /* Resmin sayfayı tamamen kaplamasını sağlar */
    margin:0;
    }
</style>
<body>
    <div class="container">
        <div class="login-form">
            <h1>Yönetim Paneli</h1>
           
            <h2 class="mt-5">Randevular</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Kullanıcı</th>
                        <th>Zaman</th>
                        <th>Durum</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT r.*, k.Ad_Soyad FROM randevular r JOIN kullanicilar k ON r.kullanici_id = k.id WHERE r.berber_id = $berber_id");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['Ad_Soyad']}</td>
                                <td>{$row['randevu_zamani']}</td>
                                <td>{$row['durum']}</td>
                                <td>
                                    <form method='POST' action=''>
                                        <input type='hidden' name='randevu_id' value='{$row['id']}'>
                                        <select name='durum' class='form-select'>
                                            <option value='beklemede' " . ($row['durum'] == 'beklemede' ? 'selected' : '') . ">Beklemede</option>
                                            <option value='onaylandi' " . ($row['durum'] == 'onaylandi' ? 'selected' : '') . ">Onaylandı</option>
                                            <option value='iptal' " . ($row['durum'] == 'iptal' ? 'selected' : '') . ">İptal</option>
                                        </select>
                                        <button type='submit' class='btn mt-2'>Güncelle</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <a href="index.php"><button class="btn">Geri</button></a>
    </div>
    
</body>
</html>