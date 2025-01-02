<?php
session_start();
require 'db.php'; // Veritabanı bağlantısı

// Kullanıcı giriş yaptı mı kontrol et
if (!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit();
}

$kullanici_id = $_SESSION['kullanici_id'];

// Veritabanından kullanıcı bilgilerini al
$query = "SELECT * FROM kullanicilar WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $kullanici_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Profil güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Formdan gelen verileri al
    $kullanici_adi = isset($_POST['kullanici_adi']) ? $_POST['kullanici_adi'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $sifre = isset($_POST['sifre']) ? $_POST['sifre'] : '';

    // Verilerin boş olmadığından emin ol
    if (empty($kullanici_adi) || empty($email)) {
        $message = "Kullanıcı adı ve E-posta alanları boş olamaz.";
    } else {
        // Şifre boş değilse, düz metin olarak kaydet
        if (!empty($sifre)) {
            $update_query = "UPDATE kullanicilar SET kullanici_adi = ?, email = ?, sifre = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("sssi", $kullanici_adi, $email, $sifre, $kullanici_id);
        } else {
            // Şifre boş ise sadece diğer bilgileri güncelle
            $update_query = "UPDATE kullanicilar SET kullanici_adi = ?, email = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ssi", $kullanici_adi, $email, $kullanici_id);
        }

        // Veritabanı sorgusunun başarılı olup olmadığını kontrol et
        if ($stmt->execute()) {
            $message = "Profil başarıyla güncellendi!";
        } else {
            $message = "Bir hata oluştu, lütfen tekrar deneyin.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Profilim</title>
    <link href="css/main.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="images/fav.png"/>
</head>
<style>
    body {
    background-image: url('images/7.jpg');  /* Arka plan resmi yolu */
    background-size: cover;  /* Resmin sayfayı tamamen kaplamasını sağlar */
    margin:0;
    }
</style>
<body>
    <div class="container">
        <div class="login-form">
            <h1>Profil Bilgilerim</h1>
            
            <!-- Hata veya başarı mesajı -->
            <?php if (isset($message)): ?>
                <div class="alert <?php echo isset($message) && strpos($message, 'başarıyla') !== false ? 'alert-success' : 'alert-danger'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST">
                    <div class="form-group">
                        <label for="kullanici_adi">Kullanıcı Adı:</label>
                        <input type="text" name="kullanici_adi" id="kullanici_adi" value="<?php echo isset($user['kullanici_adi']) ? $user['kullanici_adi'] : ''; ?>" required class="form-control" >
                    </div>

                    <div class="form-group">
                        <label for="ad_soyad">Ad Soyad:</label>
                        <input type="text" name="ad_soyad" id="ad_soyad" value="<?php echo isset($user['Ad_Soyad']) ? $user['Ad_Soyad'] : ''; ?>" required class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="email">E-posta:</label>
                        <input type="email" name="email" id="email" value="<?php echo isset($user['email']) ? $user['email'] : ''; ?>" required class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="sifre">Yeni Şifre (Boş bırakılması durumunda eski şifre geçerli olacaktır):</label>
                        <input type="password" name="sifre" id="sifre" class="form-control">
                    </div>

                    <button type="submit" class="btn">Profili Güncelle</button>
                </form>


            <a href="index.php"><button class="btn">Geri</button></a>
        </div>
    </div>
</body>
</html>
