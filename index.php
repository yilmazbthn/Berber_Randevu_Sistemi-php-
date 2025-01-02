<?php
session_start();
require_once 'db.php'; 

//oturum kontrolü
if (!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit;
}

$kullanici_id = $_SESSION['kullanici_id'];
$query = "SELECT rol, Ad_Soyad FROM kullanicilar WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $kullanici_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$rol = $row['rol']; 
$ad_soyad = $row['Ad_Soyad'];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>YILMAZ HAİR DESİGN Randevu Sistemi</title>
    <link href="css/main.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="images/fav.png"/>
    <style>
        body {
            background-image: url('images/bg.jpg');  
            background-size: cover; 
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 50px auto;
            width: 40%;
            justify-content: center; 
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
            margin: 10px;
            width: auto;
            white-space: nowrap;
        }

        .btn:hover {
            background: #555;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>YILMAZ HAİR DESİGN - Randevu Sistemi</h1>
    
        <p style="text-align: center; font-size: 18px; color: #333;">Hoşgeldiniz, <?php echo $ad_soyad; ?>!</p>


        <div class="row">
            <a href="randevular.php" class="btn">Randevu Al</a>
            <a href="randevularim.php" class="btn">Randevularım</a>
            <a href="profil.php" class="btn">Profilim</a>

            <!-- Yönetim Paneli, yalnızca berber rolüne sahip kullanıcılar için aktif olacak -->
            <?php if ($rol == 'berber'): ?>
                <a href="yonetim.php" class="btn">Yönetim Paneli</a>
            <?php endif; ?>
            
            <a href="cikis.php" class="btn">Çıkış Yap</a>
        </div>
    </div>
</body>
</html>
