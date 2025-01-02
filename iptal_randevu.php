<?php
require 'db.php';
session_start();

// Kullanıcı oturumu kontrolü
if (!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit();
}

// Randevu ID'si alın
if (isset($_GET['id'])) {
    $randevu_id = $_GET['id'];
} else {
    header("Location: randevularim.php");
    exit();
}

$kullanici_id = $_SESSION['kullanici_id'];

// Randevunun geçerli olup olmadığını kontrol et
$query = "SELECT * FROM randevular WHERE id = ? AND kullanici_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $randevu_id, $kullanici_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Randevu bulunamadı veya kullanıcıya ait değil
    echo "<script>alert('Randevuyu iptal ederken bir sorun oluştu!'); window.location.href = 'randevularim.php';</script>";
    exit();
}

// Randevuyu iptal et
$delete_query = "DELETE FROM randevular WHERE id = ? AND kullanici_id = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("ii", $randevu_id, $kullanici_id);

if ($stmt->execute()) {
    // Başarılı bir şekilde iptal edildi
    echo "<script>
            alert('Randevunuz başarıyla iptal edildi!');
            window.location.href = 'randevularim.php';
          </script>";
} else {
    // Hata durumunda
    echo "<script>
            alert('Randevu iptal edilirken bir hata oluştu!');
            window.location.href = 'randevularim.php';
          </script>";
}
?>
