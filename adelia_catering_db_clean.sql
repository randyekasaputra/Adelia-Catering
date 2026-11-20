-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: adelia_catering
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `blocked_dates`
--

DROP TABLE IF EXISTS `blocked_dates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blocked_dates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blocked_dates_date_unique` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blocked_dates`
--

LOCK TABLES `blocked_dates` WRITE;
/*!40000 ALTER TABLE `blocked_dates` DISABLE KEYS */;
/*!40000 ALTER TABLE `blocked_dates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_settings`
--

DROP TABLE IF EXISTS `booking_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_settings`
--

LOCK TABLES `booking_settings` WRITE;
/*!40000 ALTER TABLE `booking_settings` DISABLE KEYS */;
INSERT INTO `booking_settings` VALUES (1,'max_orders_per_day','100','2026-05-19 09:39:40','2026-06-01 21:11:18'),(2,'min_advance_days','3','2026-05-19 09:39:40','2026-05-19 09:39:40');
/*!40000 ALTER TABLE `booking_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'makanan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=167 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,'Roti Buttercream','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page02_img09_266x188.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:11:50'),(2,'Roti Boy Cup','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4900,'/images/menu_extracted/page02_img06_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:11:37'),(3,'Roti Gendon','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page02_img08_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:11:50'),(4,'Roti Bunga','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4300,'/images/menu_extracted/page02_img03_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:11:07'),(5,'Roti Pisang','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page02_img07_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:06:46'),(6,'Roti Konde','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page02_img04_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:05:25'),(7,'Roti Daging','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4500,'/images/menu_extracted/page03_img09_236x165.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:13:30'),(8,'Roti Cup Sosis','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4500,'/images/menu_extracted/page03_img06_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:13:01'),(9,'Roti Boy','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3800,'/images/menu_extracted/page03_img08_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:13:30'),(10,'Roti Kipas','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4900,'/images/menu_extracted/page03_img03_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:13:16'),(11,'Roti Gulung Abon','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4000,'/images/menu_extracted/page03_img07_422x234.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(12,'Roti Sosis Besar','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4900,'/images/menu_extracted/page03_img04_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:12:39'),(13,'Roti Pisang Mika Besar','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4000,'/images/menu_extracted/page04_img07_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:14:17'),(14,'Roti Goreng Coklat','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4200,'/images/menu_extracted/page04_img06_474x270.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:14:26'),(15,'Burger','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3900,'/images/menu_extracted/page04_img08_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:14:17'),(16,'Hot Dog','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4800,'/images/menu_extracted/page04_img03_333x720.png',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:14:12'),(17,'Roti Pisang Mika','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3900,'/images/menu_extracted/page04_img09_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(18,'Roti Cup Coklat','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',13000,'/images/menu_extracted/page04_img04_315x560.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:14:26'),(19,'Roti Pic Man','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page05_img09_360x640.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(20,'Roti Fla Tengah','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page05_img06_315x560.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(21,'Roti Selai','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3900,'/images/menu_extracted/page05_img04_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:15:39'),(22,'Roti Full Cream','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page05_img03_300x168.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:15:39'),(23,'Roti Fla Samping','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3200,'/images/menu_extracted/page05_img07_315x560.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:15:15'),(24,'Roti Coklat','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page05_img08_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:15:28'),(25,'Roti Ayam','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page06_img09_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:17:15'),(26,'Pizza','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page06_img06_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 18:55:24'),(27,'Roti Abon','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page06_img08_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:17:06'),(28,'Roti Sosis','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3800,'/images/menu_extracted/page06_img03_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:17:15'),(29,'Roti Sosis Bunga','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4000,'/images/menu_extracted/page06_img07_200x250.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 18:55:24'),(30,'Roti Kelapa','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4000,'/images/menu_extracted/page06_img04_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:16:55'),(31,'Croissant Blok','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4500,'/images/menu_extracted/page07_img09_274x182.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:18:14'),(32,'Donat Blok Kacang','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4600,'/images/menu_extracted/page07_img08_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:17:54'),(33,'Donat Blok Putih','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4500,'/images/menu_extracted/page07_img03_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:17:59'),(34,'Donat 3 Warna','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3800,'/images/menu_extracted/page07_img07_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:18:14'),(35,'Donat Blok Pink','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4500,'/images/menu_extracted/page07_img04_336x336.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:17:49'),(36,'Donat Blok Sate','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4700,'/images/menu_extracted/page07_img06_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:18:03'),(37,'Donat Meses Keju','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4200,'/images/menu_extracted/page08_img09_315x560.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:18:44'),(38,'Donat Pink','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4500,'/images/menu_extracted/page08_img06_315x560.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:18:44'),(39,'Donat Salju','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4200,'/images/menu_extracted/page08_img08_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:18:33'),(40,'Donat Meses','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4600,'/images/menu_extracted/page08_img03_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:18:33'),(41,'Donat Keju','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page08_img07_274x182.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:18:38'),(42,'Roti Goreng Ayam','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4500,'/images/menu_extracted/page08_img04_266x188.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:18:38'),(43,'Pastry Contong','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4000,'/images/menu_extracted/page09_img09_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(44,'Pastry Pisang','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page09_img03_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:19:27'),(45,'Croissant Sosis','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4200,'/images/menu_extracted/page09_img04_191x264.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(46,'Croissant Keju','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page09_img06_222x480.png',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:19:27'),(47,'Pastry Segitiga','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3300,'/images/menu_extracted/page09_img07_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(48,'Pastry Isi Selai','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3800,'/images/menu_extracted/page09_img08_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(49,'Donat Meses Warna','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4000,'/images/menu_extracted/page10_img06_360x640.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(50,'Donat Sate','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4300,'/images/menu_extracted/page10_img08_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:20:03'),(51,'Donat Blok 3 Warna','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4500,'/images/menu_extracted/page10_img03_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(52,'Donat Blok','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4500,'/images/menu_extracted/page10_img07_315x560.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:20:03'),(53,'Bomboloni','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4600,'/images/menu_extracted/page10_img04_274x182.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:20:37'),(54,'Bolu Gula Merah','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4000,'/images/menu_extracted/page10_img09_329x233.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:20:37'),(55,'Pie Susu','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4200,'/images/menu_extracted/page11_img08_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:21:21'),(56,'Sus Fla','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page11_img03_274x182.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:21:28'),(57,'Pisang Bolen','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page11_img04_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:20:59'),(58,'Pastry Apel','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page11_img06_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:21:41'),(59,'Sus Coklat','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page11_img07_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:21:11'),(60,'Pie Nanas','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page11_img09_266x188.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:21:41'),(61,'Sus Buah Fla','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page12_img09_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:22:34'),(62,'Pie Brownies','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page12_img06_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:22:11'),(63,'Sus Ayam Selada','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4000,'/images/menu_extracted/page12_img08_360x640.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:22:11'),(64,'Eclair','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3800,'/images/menu_extracted/page12_img03_274x182.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:22:34'),(65,'Sus Fla Sate','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3900,'/images/menu_extracted/page12_img07_360x640.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:22:21'),(66,'Pie Buah','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page12_img04_266x188.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:22:04'),(67,'Cake Keju','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3800,'/images/menu_extracted/page13_img09_475x317.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(68,'Rainbow Cake','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3900,'/images/menu_extracted/page13_img08_266x188.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:25:46'),(69,'Cake Pisang','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page13_img03_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(70,'Cake Zebra','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page13_img07_266x188.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:23:08'),(71,'Sus Pisang','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3800,'/images/menu_extracted/page13_img04_266x188.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:23:08'),(72,'Sus Gulung Rogut','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4000,'/images/menu_extracted/page13_img06_324x216.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:23:16'),(73,'Bolu Ban','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3900,'/images/menu_extracted/page14_img09_360x640.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:25:09'),(74,'Cake Lapis Pelangi','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page14_img04_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:26:30'),(75,'Brownies Ketan Hitam','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page14_img08_360x640.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:26:30'),(76,'Brownies Kukus','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4800,'/images/menu_extracted/page14_img03_332x242.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:24:11'),(77,'Brownies Keju','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4400,'/images/menu_extracted/page14_img07_350x350.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:24:11'),(78,'Bolu Gulung Blok','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page15_img09_202x250.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:31:07'),(79,'Bolu Gulung','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page15_img08_266x188.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:29:54'),(80,'Bolu Gulung Pelangi','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3800,'/images/menu_extracted/page15_img03_315x560.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:30:06'),(81,'Bolu Gulung Moca','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page15_img07_277x277.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:31:07'),(82,'Chiffon Cake','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page15_img04_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(83,'Bakpao','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page15_img06_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:29:54'),(84,'Roll Pandan','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4900,'/images/menu_extracted/page16_img04_315x560.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(85,'Cake Marmer Kukus','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3800,'/images/menu_extracted/page16_img06_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:30:44'),(86,'Bolu Gulung Moca Kukus','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page16_img08_315x560.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:31:33'),(87,'Pandan','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page16_img07_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:31:33'),(88,'Pelangi','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3600,'/images/menu_extracted/page17_img09_480x270.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:38:07'),(89,'Black Forest Roll','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page17_img08_315x560.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:38:16'),(90,'Black forest','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3900,'/images/menu_extracted/page17_img03_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:37:45'),(91,'Cake Gulung Zebra','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3800,'/images/menu_extracted/page17_img07_315x560.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:37:56'),(92,'Cake Tutul','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3600,'/images/menu_extracted/page17_img04_360x640.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:38:16'),(93,'Cake Tutul Hijau','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3600,'/images/menu_extracted/page17_img06_360x640.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:37:34'),(94,'Bolu Lapis','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4300,'/images/menu_extracted/page18_img09_315x560.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:40:41'),(95,'Bolu Gulung Keju','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page18_img06_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:38:49'),(96,'Putri Salju','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page18_img08_315x560.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:40:00'),(97,'Pudding Coklat','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page18_img03_274x182.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:39:12'),(98,'Mandarin','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3900,'/images/menu_extracted/page18_img07_480x270.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:40:41'),(99,'Lapis','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page18_img04_258x193.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(100,'Pastel Ayam','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page19_img08_560x315.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(101,'Pastel Sayur','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4400,'/images/menu_extracted/page19_img09_560x315.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(102,'Pastel Pisang','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page19_img03_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:41:26'),(103,'Panada','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page19_img07_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:41:17'),(104,'Agar Agar Busa','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page19_img04_274x182.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:41:07'),(105,'Bolu Kukus','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page19_img06_518x291.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:41:26'),(106,'Samosa Ayam','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page20_img06_182x274.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:42:24'),(107,'Pastel Sayur Bulat','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3800,'/images/menu_extracted/page20_img07_315x560.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(108,'Pastel Daun Sayur','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4000,'/images/menu_extracted/page20_img08_168x300.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(109,'Pastel Daun Ayam','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page20_img03_315x560.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:42:32'),(110,'Samosa Sayur','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page20_img04_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:42:32'),(111,'Pastel Ayam Bulat','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4000,'/images/menu_extracted/page20_img09_258x193.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(112,'Sosis isi Ayam','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page21_img09_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:43:37'),(113,'Kue Lumpur','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page21_img08_274x182.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:45:19'),(114,'Apem Jowo','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4300,'/images/menu_extracted/page21_img03_274x182.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:44:46'),(115,'Coro Bikang','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page21_img07_232x217.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:45:19'),(116,'Schotel','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4000,'/images/menu_extracted/page21_img04_266x188.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:44:26'),(117,'Apem Inggris','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page21_img06_236x167.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:45:03'),(118,'Martabak','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page22_img09_400x368.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(119,'Lumpia Rebung','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page22_img06_200x133.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:45:45'),(120,'Lumpia Basah','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3200,'/images/menu_extracted/page22_img08_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:46:05'),(121,'Risol Mayo','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page22_img03_444x249.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:29:17'),(122,'Risoles','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page22_img07_300x168.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:46:05'),(123,'Lumpia Goreng','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4000,'/images/menu_extracted/page22_img04_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:45:45'),(124,'Tiwul Singapore','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page23_img09_120x170.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:47:19'),(125,'Sentiling','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page23_img08_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:47:19'),(126,'Moto Belong','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3600,'/images/menu_extracted/page23_img03_160x120.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:47:00'),(127,'Klepon','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4000,'/images/menu_extracted/page23_img07_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:47:00'),(128,'Kroket','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page23_img04_276x183.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:46:34'),(129,'Getuk Lindri','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page23_img06_274x182.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:46:44'),(130,'Gemblong','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3600,'/images/menu_extracted/page24_img09_315x560.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(131,'Wajik','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4000,'/images/menu_extracted/page24_img06_480x270.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(132,'Arem Arem Ayam','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4000,'/images/menu_extracted/page24_img04_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(133,'Nagasari Daun','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4500,'/images/menu_extracted/page24_img03_220x140.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:47:48'),(134,'Arem Arem Tahu','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4000,'/images/menu_extracted/page24_img07_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:47:52'),(135,'Cantik Manis','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4000,'/images/menu_extracted/page24_img08_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:47:52'),(136,'Misoa','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3800,'/images/menu_extracted/page25_img09_315x560.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(137,'Tahu Isi Mie','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page25_img08_258x193.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:49:01'),(138,'Pankuk Kelapa','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3200,'/images/menu_extracted/page25_img03_266x190.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:49:01'),(139,'Tahu Sayur Basah','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page25_img07_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(140,'Pankuk','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3800,'/images/menu_extracted/page25_img04_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:48:27'),(141,'Pankuk Pisang','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3200,'/images/menu_extracted/page25_img06_274x182.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:48:45'),(142,'Mendut','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4500,'/images/menu_extracted/page26_img09_302x167.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:28:52'),(143,'Pisang Goreng Krispi','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3600,'/images/menu_extracted/page26_img06_274x182.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:28:31'),(144,'Kura Kura','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3600,'/images/menu_extracted/page26_img08_250x250.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:28:52'),(145,'Tahu Bakso','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page26_img03_223x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:28:12'),(146,'Tahu Susur Goreng','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page26_img07_374x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:28:39'),(147,'Sarang Burung','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3600,'/images/menu_extracted/page26_img04_224x224.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:28:04'),(148,'Muffin','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4100,'/images/menu_extracted/page27_img06_400x400.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(149,'Nagasari','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page27_img08_375x250.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:51:18'),(150,'Bandung','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3800,'/images/menu_extracted/page27_img07_311x311.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(151,'Semar Mendem','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4000,'/images/menu_extracted/page27_img03_277x277.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:50:32'),(152,'Ketan Goreng','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3700,'/images/menu_extracted/page27_img09_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 20:51:18'),(153,'Onde Onde','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3600,'/images/menu_extracted/page27_img04_233x233.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(154,'Roti 3 Rasa','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',4600,'/images/menu_extracted/page28_img03_369x800.png',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(155,'Pudding Buah','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3800,'/images/menu_extracted/page28_img06_270x480.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:27:42'),(156,'Cake Pyramid','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3500,'/images/menu_extracted/page28_img08_333x720.png',NULL,'snack','2026-05-31 22:23:57','2026-06-01 19:27:42'),(157,'Gulung','Camilan tradisional pilihan Adelia Catering, dibuat segar setiap hari dengan bahan-bahan berkualitas.',3800,'/images/menu_extracted/page28_img07_300x300.jpeg',NULL,'snack','2026-05-31 22:23:57','2026-05-31 22:23:57'),(158,'Nasi Box Bandeng Presto','Menu catering lezat dari Adelia Catering, cocok untuk berbagai acara dan hajatan.',25000,'/images/menu_extracted/page35_img03_450x800.jpeg',NULL,'makanan','2026-05-31 22:23:57','2026-05-31 22:23:57'),(159,'Nasi Box Ikan Goreng','Menu catering lezat dari Adelia Catering, cocok untuk berbagai acara dan hajatan.',25000,'/images/menu_extracted/page35_img04_450x800.jpeg',NULL,'makanan','2026-05-31 22:23:57','2026-05-31 22:23:57'),(160,'Nasi Kuning','Menu catering lezat dari Adelia Catering, cocok untuk berbagai acara dan hajatan.',25000,'/images/menu_extracted/page36_img05_315x560.jpeg',NULL,'makanan','2026-05-31 22:23:57','2026-05-31 22:34:45'),(161,'Nasi Box Ayam Katsu','Menu catering lezat dari Adelia Catering, cocok untuk berbagai acara dan hajatan.',25000,'/images/menu_extracted/page36_img03_450x800.jpeg',NULL,'makanan','2026-05-31 22:23:57','2026-05-31 22:34:45'),(162,'Nasi Uduk','Menu catering lezat dari Adelia Catering, cocok untuk berbagai acara dan hajatan.',25000,'/images/menu_extracted/page37_img03_333x333.jpeg',NULL,'makanan','2026-05-31 22:23:57','2026-05-31 22:23:57'),(163,'Berkat Rames','Menu catering lezat dari Adelia Catering, cocok untuk berbagai acara dan hajatan.',49000,'/images/menu_extracted/page38_img04_360x640.jpeg',NULL,'makanan','2026-05-31 22:23:57','2026-05-31 22:23:57'),(164,'Paket Mapati / Mitoni','Menu catering lezat dari Adelia Catering, cocok untuk berbagai acara dan hajatan.',52000,'/images/menu_extracted/page39_img03_450x800.jpeg',NULL,'makanan','2026-05-31 22:23:57','2026-05-31 22:23:57'),(165,'Dekem Ayam','Menu catering lezat dari Adelia Catering, cocok untuk berbagai acara dan hajatan.',450000,'/images/menu_extracted/page40_img03_480x321.jpeg',NULL,'makanan','2026-05-31 22:23:57','2026-05-31 22:30:01'),(166,'Paket Tumpeng Nasi Kuning','Menu catering lezat dari Adelia Catering, cocok untuk berbagai acara dan hajatan.',450000,'/images/menu_extracted/page40_img04_480x360.jpeg',NULL,'makanan','2026-05-31 22:23:57','2026-05-31 22:30:01');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2026_04_16_060001_create_menus_table',1),(6,'2026_04_16_060002_create_orders_table',1),(7,'2026_04_16_060003_create_order_items_table',1),(8,'2026_04_16_061823_add_snap_token_to_orders_table',2),(9,'2026_05_07_085354_add_payment_type_to_orders_table',3),(10,'2026_05_20_000001_create_booking_settings_table',4),(11,'2026_05_20_000002_create_blocked_dates_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `menu_id` bigint unsigned NOT NULL,
  `menu_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int NOT NULL,
  `price` int NOT NULL,
  `subtotal` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_menu_id_foreign` (`menu_id`),
  CONSTRAINT `order_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_wa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_date` datetime NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `payment_scheme` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` int NOT NULL,
  `dp_amount` int NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `tracking_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `snap_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin@admin.com',NULL,'$2y$12$TJf72btc.1BI2n8yNQOJ4eRMjQ4ondlsfoH14fNEMELeXAMyZftMW',NULL,'2026-04-15 23:01:42','2026-04-15 23:01:42');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-12  9:28:34
