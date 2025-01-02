<?php
require 'db.php';
session_start();

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit();
}

$randevu_id = $_GET['id'] ?? null;

if (!$randevu_id) {
    header("Location: randevularim.php");
    exit();
}

// Randevu detaylarını çek
$query = "SELECT * FROM randevular WHERE id = ? AND kullanici_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $randevu_id, $_SESSION['kullanici_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: randevularim.php");
    exit();
}

$randevu = $result->fetch_assoc();

// Hizmeti güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hizmet_id = $_POST['hizmet_id'];

    $update_query = "UPDATE randevular SET hizmet_id = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $hizmet_id, $randevu_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Hizmet başarıyla güncellendi!');
                window.location.href = 'randevularim.php';
              </script>";
        exit();
    } else {
        echo "<script>alert('Güncelleme başarısız!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Randevu Düzenle</title>
    <link href="css/main.css" rel="stylesheet">
</head>
<style>
    body {
    background-image: url('images/8.jpg');  /* Arka plan resmi yolu */
    background-size: cover;  /* Resmin sayfayı tamamen kaplamasını sağlar */
    margin:0;
    }
</style>
<body>
    <div class="container">
        <h2>Randevu Düzenle</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="hizmet_id">Hizmet Seç:</label>
                <select name="hizmet_id" id="hizmet_id" class="form-control" required>
                    <?php
                    $hizmetler = $conn->query("SELECT * FROM hizmetler");
                    while ($hizmet = $hizmetler->fetch_assoc()) {
                        $selected = $hizmet['id'] == $randevu['hizmet_id'] ? 'selected' : '';
                        echo "<option value='{$hizmet['id']}' $selected>{$hizmet['hizmet_adi']} - {$hizmet['fiyat']} TL</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn">Güncelle</button>
            <a href="randevularim.php"><button type="button" class="btn">Geri</button></a>
        </form>
    </div>
</body>
</html>
