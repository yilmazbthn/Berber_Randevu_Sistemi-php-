-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 02 Oca 2025, 20:19:38
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `berber_randevu`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hizmetler`
--

CREATE TABLE `hizmetler` (
  `id` int(11) NOT NULL,
  `hizmet_adi` varchar(255) NOT NULL,
  `fiyat` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `hizmetler`
--

INSERT INTO `hizmetler` (`id`, `hizmet_adi`, `fiyat`) VALUES
(1, 'Çocuk Tıraşı', 200.00),
(2, 'Damat Tıraşı', 1000.00),
(3, 'Fön', 100.00),
(4, 'Saç Boyama', 600.00),
(5, 'Saç Kesim', 250.00),
(6, 'Saç Sakal Kesim', 350.00),
(7, 'Sakal Kesim', 150.00);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

CREATE TABLE `kullanicilar` (
  `id` int(11) NOT NULL,
  `kullanici_adi` varchar(50) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `rol` enum('kullanici','berber') NOT NULL,
  `Ad_Soyad` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `kullanicilar`
--

INSERT INTO `kullanicilar` (`id`, `kullanici_adi`, `sifre`, `email`, `rol`, `Ad_Soyad`) VALUES
(1, 'admin', '1234', 'yilmazbthn20@gmail.com', 'berber', 'Batuhan Yılmaz'),
(2, 'berber1', '1234', 'berber1@example.com', 'berber', 'Kerem Yılmaz'),
(3, 'berber2', '1234', 'berber2@example.com', 'berber', 'Asım Tepe'),
(4, 'Kullanici1', '1234', 'berber3@example.com', 'kullanici', 'Ahmet Gündüz'),
(5, 'Kullanici2', '1234', 'berber4@example.com', 'kullanici', 'Eray Başaran'),
(6, 'Kullanici3', '1234', 'berber5@example.com', 'kullanici', 'Mehmet Tulupçu'),
(23, 'selo', '1234', 'batuhan@example.com', 'kullanici', 'Selami Turan'),
(24, 'tazec', '1234', 'batu@gmail.com', 'kullanici', 'can Taze');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `randevular`
--

CREATE TABLE `randevular` (
  `id` int(11) NOT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `berber_id` int(11) DEFAULT NULL,
  `randevu_zamani` datetime NOT NULL,
  `durum` enum('beklemede','onaylandi','iptal') DEFAULT 'beklemede',
  `hizmet_id` int(11) DEFAULT NULL,
  `hizmet_fiyat` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `randevular`
--

INSERT INTO `randevular` (`id`, `kullanici_id`, `berber_id`, `randevu_zamani`, `durum`, `hizmet_id`, `hizmet_fiyat`) VALUES
(41, 1, 2, '2025-01-10 16:30:00', 'beklemede', 2, 1000.00);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `hizmetler`
--
ALTER TABLE `hizmetler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `randevular`
--
ALTER TABLE `randevular`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kullanici_id` (`kullanici_id`),
  ADD KEY `berber_id` (`berber_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `hizmetler`
--
ALTER TABLE `hizmetler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `kullanicilar`
--
ALTER TABLE `kullanicilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Tablo için AUTO_INCREMENT değeri `randevular`
--
ALTER TABLE `randevular`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `randevular`
--
ALTER TABLE `randevular`
  ADD CONSTRAINT `randevular_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`),
  ADD CONSTRAINT `randevular_ibfk_2` FOREIGN KEY (`berber_id`) REFERENCES `kullanicilar` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
