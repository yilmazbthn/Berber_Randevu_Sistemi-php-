<?php
require 'db.php';

session_start();

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kullanici_adi = $_POST['kullanici_adi'];
    $sifre = $_POST['sifre'];

    // Veritabanı sorgusu
    $sql = "SELECT * FROM kullanicilar WHERE kullanici_adi = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
    $stmt->bind_param("s", $kullanici_adi);
    $stmt->execute();
    $result = $stmt->get_result();
        $kullanici = $result->fetch_assoc();

        // Düz metin şifre doğrulama
        if ($kullanici && $sifre == $kullanici['sifre']) {
            // Başarılı giriş
            $_SESSION['kullanici_id'] = $kullanici['id'];
            $_SESSION['rol'] = $kullanici['rol'];
            header("Location: index.php");
            exit();
        } else {
            $error_message = 'Kullanıcı adı veya parola hatalı!';
        }
        $stmt->close();
    } else {
        $error_message = 'Veritabanı hatası: ' . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
    <link href="css/main.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="images/fav.png"/>
</head>
<style>
    body {
    background-image: url('images/2.jpeg');  /* Arka plan resmi yolu */
    background-size: cover;  /* Resmin sayfayı tamamen kaplamasını sağlar */
    margin:0;
    }
</style>
<body>
    <div class="container">
        <div class="login-form">
        <h1>Giriş Yap</h1>
            <?php if ($error_message): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="" class="validate-form">
                <div class="form-group">
                    <input type="text" name="kullanici_adi" required placeholder="Kullanıcı Adı">
                </div>
                <div class="form-group">
                    <input type="password" name="sifre" required placeholder="Parola">
                </div>
                <button type="submit" class="btn">Giriş Yap</button>
               
        </form>
        <a href="kayit.php"><button type="" class="btn">Kayıt Ol</button></a> 
    </div>
    </div>
    <script src="js/main.js"></script>
</body>
</html>