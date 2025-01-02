<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ad_soyad = $_POST['Ad_Soyad'];
    $kullanici_adi = $_POST['kullanici_adi'];
    $sifre = $_POST['sifre']; // Düz metin şifre
    $email = $_POST['email'];
    $rol = $_POST['rol']; // 'kullanici' veya 'berber'

    $sql = "INSERT INTO kullanicilar (Ad_Soyad,kullanici_adi, sifre, email, rol) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss",$ad_soyad, $kullanici_adi, $sifre, $email, $rol);

    if ($stmt->execute()) {
        echo "
        <script>
            alert('Kayıt Başarılı !');
            setTimeout(function() {
                window.location.href = 'giris.php';
            });
        </script>
    ";
    } else {
        echo "Kayıt başarısız: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <link href="css/main.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="images/fav.png"/>
</head>
<style>
    body {
    background-image: url('images/6.jpg');  /* Arka plan resmi yolu */
    background-size: cover;  /* Resmin sayfayı tamamen kaplamasını sağlar */
    margin:0;
    }
</style>
<body>
    <div class="container">
        <div class="login-form">
            <h1>Kayıt Ol</h1>
            <form method="POST" action="" class="validate-form">
            <div class="form-group">
                    <input type="text" name="Ad_Soyad" required placeholder="Ad Soyad">
                </div>
                <div class="form-group">
                    <input type="text" name="kullanici_adi" required placeholder="Kullanıcı Adı">
                </div>
                <div class="form-group">
                    <input type="password" name="sifre" required placeholder="Parola">
                </div>
                <div class="form-group">
                    <input type="email" name="email" required placeholder="Email">
                </div>
                <div class="form-group">
                    <select name="rol" required>
                        <option value="">Rol Seçin</option>
                        <option value="kullanici">Kullanıcı</option>
                        <option value="berber">Berber</option>
                    </select>
                </div>
                <button type="submit" class="btn">Kayıt Ol</button>
            </form>
        </div>
    </div>
    <script src="js/main.js"></script>
</body>
</html>