<?php
require 'db.php';

session_start();

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit();
}

$kullanici_id = $_SESSION['kullanici_id'];
$error_message = '';
$hizmet_fiyat = 0;  // Hizmetin fiyatı


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $berber_id = $_POST['berber_id'];
    $randevu_tarihi = $_POST['randevu_tarihi'];
    $randevu_saati = $_POST['randevu_saati'];
    $randevu_zamani = $randevu_tarihi . ' ' . $randevu_saati . ':00';
    $hizmet_id = $_POST['hizmet_id'];

    // Hizmetin fiyatını al
    $hizmet_query = "SELECT fiyat FROM hizmetler WHERE id = ?";
    $stmt = $conn->prepare($hizmet_query);
    $stmt->bind_param("i", $hizmet_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hizmet_fiyat = $row['fiyat'];
    }

    // Aynı berber, tarih ve saatte mevcut bir randevu olup olmadığını kontrol et
    $check_sql = "SELECT * FROM randevular WHERE berber_id = ? AND randevu_zamani = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("is", $berber_id, $randevu_zamani);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = 'Bu berber için seçilen tarih ve saatte zaten bir randevu var. Lütfen başka bir zaman seçin.';
    } else {
        // Randevu zamanının uygun olduğu tespit edildiğinde randevuyu ekle
        $sql = "INSERT INTO randevular (kullanici_id, berber_id, randevu_zamani, hizmet_id, hizmet_fiyat) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisid", $kullanici_id, $berber_id, $randevu_zamani, $hizmet_id, $hizmet_fiyat);

        if ($stmt->execute()) {
            echo "
            <script>
                alert('Randevu alındı!');
                setTimeout(function() {
                    window.location.href = 'randevularim.php';
                });
            </script>
        ";
        } else {
            echo "<script>
            alert('Randevu alınamadı!');
        </script>";
        }
    }
}

// Alınmış randevu saatlerini çek
$alınmıs_randevular = [];
if (isset($_POST['berber_id'])) {
    $berber_id = $_POST['berber_id'];
    $result = $conn->query("SELECT DATE_FORMAT(randevu_zamani, '%Y-%m-%d %H:%i') as randevu_zamani FROM randevular WHERE berber_id = $berber_id");
    while ($row = $result->fetch_assoc()) {
        $alınmıs_randevular[] = $row['randevu_zamani'];
    }
}

// Randevu listeleme
$result = $conn->query("SELECT r.*, k.kullanici_adi as berber_adi, h.hizmet_adi, h.fiyat FROM randevular r 
                        JOIN kullanicilar k ON r.berber_id = k.id 
                        JOIN hizmetler h ON r.hizmet_id = h.id 
                        WHERE r.kullanici_id = $kullanici_id");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Randevu Al</title>
    <link href="css/main.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="images/fav.png"/>
</head>
<style>
    body {
    background-image: url('images/3.jpeg'); 
    background-size: cover;  
    margin:0;
    }
</style>
<body> 
    <div class="container">
        <div class="login-form">
            <h1>Randevu Al</h1>
            <?php if ($error_message): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="berber_id">Berber Seç:</label>
                    <select name="berber_id" id="berber_id" class="form-select" required>
                        <option value="">Berber Seçin</option>
                        <?php
                        $result = $conn->query("SELECT id, Ad_Soyad FROM kullanicilar WHERE rol = 'berber'");
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $selected = (isset($berber_id) && $berber_id == $row['id']) ? 'selected' : '';
                                echo "<option value='{$row['id']}' $selected>{$row['Ad_Soyad']}</option>";
                            }
                        } else {
                            echo "<option value=''>Berber Bulunamadı</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="randevu_tarihi">Randevu Tarihi:</label>
                    <input type="date" name="randevu_tarihi" id="randevu_tarihi" required class="form-control" min="<?= date('Y-m-d') ?>">
                </div>
                <div class="form-group">
                    <label for="randevu_saati">Randevu Saati:</label>
                    <select name="randevu_saati" id="randevu_saati" class="form-select" required>
                        <option value="">Saat Seçin</option>
                        <?php
                        $saatler = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00', '22:30', '23:00', '23:30'];
                        
                        foreach ($saatler as $saat) {
                            $datetime = isset($randevu_tarihi) ? $randevu_tarihi . ' ' . $saat : date('Y-m-d') . ' ' . $saat;
                            $disabled = in_array($datetime, $alınmıs_randevular) ? 'disabled' : '';
                            echo "<option value='$saat' $disabled>$saat</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="hizmet_id">Hizmet Seç:</label>
                    <select name="hizmet_id" id="hizmet_id" class="form-select" required>
                        <option value="">Hizmet Seçin</option>
                        <?php
                        $hizmetler = $conn->query("SELECT * FROM hizmetler");
                        while ($row = $hizmetler->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['hizmet_adi']} - {$row['fiyat']} TL</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn">Randevu Al</button>
            </form>
            <a href="index.php"><button onclick="window.history.back()" class="btn">Geri</button></a>
        </div>
    </div>
    
</body>
</html>
