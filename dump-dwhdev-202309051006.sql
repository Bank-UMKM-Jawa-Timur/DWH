-- MySQL dump 10.13  Distrib 8.0.33, for Linux (x86_64)
--
-- Host: localhost    Database: dwhdev
-- ------------------------------------------------------
-- Server version	8.0.33-0ubuntu0.20.04.2

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
-- Table structure for table `actions`
--

DROP TABLE IF EXISTS `actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `actions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `actions_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actions`
--

LOCK TABLES `actions` WRITE;
/*!40000 ALTER TABLE `actions` DISABLE KEYS */;
INSERT INTO `actions` VALUES (1,'Dashboard','2023-06-05 19:24:49','2023-06-05 19:24:49'),(2,'KKB-data pengajuan baru','2023-06-05 19:24:49','2023-06-05 19:24:49'),(3,'KKB-List','2023-06-05 19:24:49','2023-06-05 19:24:49'),(4,'KKB-upload bukti pembayaran','2023-06-05 19:24:49','2023-06-05 19:24:49'),(5,'KKB-konfirmasi bukti pembayaran','2023-06-05 19:24:49','2023-06-05 19:24:49'),(6,'KKB-atur tanggal ketersediaan unit','2023-06-05 19:24:49','2023-06-05 19:24:49'),(7,'KKB-atur tanggal penyerahan unit','2023-06-05 19:24:49','2023-06-05 19:24:49'),(8,'KKB-konfirmasi penyerahan unit','2023-06-05 19:24:49','2023-06-05 19:24:49'),(9,'KKB-upload berkas STNK','2023-06-05 19:24:49','2023-06-05 19:24:49'),(10,'KKB-upload berkas Polis','2023-06-05 19:24:49','2023-06-05 19:24:49'),(11,'KKB-upload berkas BPKB','2023-06-05 19:24:49','2023-06-05 19:24:49'),(12,'KKB-konfirmasi berkas STNK','2023-06-05 19:24:49','2023-06-05 19:24:49'),(13,'KKB-konfirmasi berkas Polis','2023-06-05 19:24:49','2023-06-05 19:24:49'),(14,'KKB-konfirmasi berkas BPKB','2023-06-05 19:24:49','2023-06-05 19:24:49'),(15,'KKB-atur imbal jasa','2023-06-05 19:24:49','2023-06-05 19:24:49'),(16,'KKB-detail data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(17,'Role-List data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(18,'Role-Tambah data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(19,'Role-Edit data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(20,'Role-Hapus data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(21,'Pengguna-List data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(22,'Pengguna-Tambah data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(23,'Pengguna-Edit data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(24,'Pengguna-Hapus data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(25,'Vendor-List data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(26,'Vendor-Tambah data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(27,'Vendor-Edit data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(28,'Vendor-Hapus data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(29,'Kategori Dokumen-List data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(30,'Kategori Dokumen-Tambah data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(31,'Kategori Dokumen-Edit data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(32,'Kategori Dokumen-Hapus data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(33,'Imbal Jasa-List data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(34,'Imbal Jasa-Tambah data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(35,'Imbal Jasa-Edit data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(36,'Imbal Jasa-Hapus data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(37,'Template Notifikasi-List data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(38,'Template Notifikasi-Tambah data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(39,'Template Notifikasi-Edit data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(40,'Template Notifikasi-Hapus data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(41,'Log aktifitas-List data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(42,'Laporan-List data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(43,'Laporan-Export data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(44,'Target-List data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(45,'Target-Tambah data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(46,'Target-Edit data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(47,'Target-Hapus data','2023-06-05 19:24:49','2023-06-05 19:24:49'),(48,'Notifikasi-List data','2023-06-05 19:24:49','2023-06-05 19:24:49');
/*!40000 ALTER TABLE `actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_categories`
--

DROP TABLE IF EXISTS `document_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document_categories_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_categories`
--

LOCK TABLES `document_categories` WRITE;
/*!40000 ALTER TABLE `document_categories` DISABLE KEYS */;
INSERT INTO `document_categories` VALUES (1,'Bukti Pembayaran','2023-06-05 19:24:49','2023-06-05 19:24:49'),(2,'Penyerahan Unit','2023-06-05 19:24:49','2023-06-05 19:24:49'),(3,'STNK','2023-06-05 19:24:49','2023-06-05 19:24:49'),(4,'Polis','2023-06-05 19:24:49','2023-06-05 19:24:49'),(5,'BPKB','2023-06-05 19:24:49','2023-06-05 19:24:49'),(6,'Bukti Pembayaran Imbal Jasa','2023-06-05 19:24:49','2023-06-05 19:24:49');
/*!40000 ALTER TABLE `document_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kredit_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `file` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `document_category_id` int unsigned NOT NULL,
  `is_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `confirm_at` date DEFAULT NULL,
  `confirm_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documents_kredit_id_foreign` (`kredit_id`),
  KEY `documents_document_category_id_foreign` (`document_category_id`),
  KEY `documents_confirm_by_foreign` (`confirm_by`),
  CONSTRAINT `documents_confirm_by_foreign` FOREIGN KEY (`confirm_by`) REFERENCES `users` (`id`),
  CONSTRAINT `documents_document_category_id_foreign` FOREIGN KEY (`document_category_id`) REFERENCES `document_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documents_kredit_id_foreign` FOREIGN KEY (`kredit_id`) REFERENCES `kredits` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
INSERT INTO `documents` VALUES (1,1,'2023-08-22','ITorxocez5e3s4ydKjyEAUNz7QhzEH1M4Xqygw7u.pdf',NULL,1,1,'2023-08-22',4,'2023-08-22 08:10:57','2023-08-22 08:11:33'),(2,1,'2023-08-26','7LeYrW9UuDzJZoN893HkEitEXZTnScCEoh6zByiN.png',NULL,2,1,'2023-08-22',2,'2023-08-22 08:12:29','2023-08-22 08:13:02'),(3,1,'2023-08-22','6RrODkTY6o0xSDRcLFWVxYJxnsOICbJugybwug1k.pdf','873678',3,1,'2023-08-22',2,'2023-08-22 08:14:13','2023-08-22 08:14:47'),(4,1,'2023-08-22','cNl6O7ch2bJ4OgOzhv1SUX2vYCLECqSTdqMdFXzo.pdf','1244654',4,1,'2023-08-22',2,'2023-08-22 08:15:32','2023-08-22 08:16:02'),(5,1,'2023-08-22','fN644PO08THeDOblmlQqSNXuxOgz0CHY0v6CFk3M.pdf','654352',5,1,'2023-08-22',2,'2023-08-22 08:15:32','2023-08-22 08:16:02'),(6,1,'2023-08-22','NzA9lOf4Cvgboz0sZpn8BT2vJ5LOBXtLpy0YYgiv.png',NULL,6,1,'2023-08-22',4,'2023-08-22 08:16:53','2023-08-22 08:17:39'),(8,2,'2023-09-03','hIxYlloeWB3ihDCLFhen3bhgfXyVqxLvLkADGkDs.pdf',NULL,1,1,'2023-09-03',4,'2023-09-03 08:45:24','2023-09-03 09:14:37'),(9,2,'2023-09-05','M5sOjzOdoIBbxd9wUsbTQnbOYnNtvvm2sdrvFLED.jpg',NULL,2,1,'2023-09-03',2,'2023-09-03 09:30:37','2023-09-03 09:54:08'),(10,2,'2023-09-03','LC8rnUYqSINnurFjD7tHUT8A3AwS7F5MYEk7rXtE.pdf','6432',3,1,'2023-09-03',2,'2023-09-03 10:21:36','2023-09-03 10:24:09'),(11,2,'2023-09-03','1NUblZ7SfoIdcL8RL5lCr6NMBuCcVGpx8iFQiiA3.pdf','75432',4,1,'2023-09-03',2,'2023-09-03 10:24:28','2023-09-03 10:24:39'),(12,2,'2023-09-03','5FPn3TI1gTyJZn9aoX6TE49ExCtQDDC2NA24FLAO.pdf','67564532',5,1,'2023-09-03',2,'2023-09-03 10:24:28','2023-09-03 10:24:39'),(13,2,'2023-09-03','cCAyvWmnDJbrYVpnyRSDOrkw8GzvQQqOLixmbMiU.jpg',NULL,6,1,'2023-09-03',4,'2023-09-03 10:35:39','2023-09-03 10:46:28'),(14,3,'2023-09-03','CU3ZrIeIFaH3oREgmGTUmUeW6pXTvePH2jtkoQlT.pdf',NULL,1,1,'2023-09-03',4,'2023-09-03 15:53:46','2023-09-03 15:57:12'),(15,3,'2023-09-07','V7nbXW3yIcHAADNAjLEjHZehwvbQlTp8JYzaiU1X.jpg',NULL,2,1,'2023-09-03',2,'2023-09-03 15:59:24','2023-09-03 16:01:54'),(16,3,'2023-09-03','VFequhpTHlD1eJzdhQZJURbqiaxp4sXr73VnLtbb.pdf','1273652',3,1,'2023-09-03',2,'2023-09-03 16:04:26','2023-09-03 16:04:53'),(17,3,'2023-09-03','haWCGnczlZ2JpGZ0xVC06hEFN8LGaDBWoT2x7kBn.pdf','654234234',4,1,'2023-09-03',2,'2023-09-03 16:04:26','2023-09-03 16:04:53'),(18,3,'2023-09-03','cKkZBfaafKAEopgRmPkUkPEH0HRKchvSSOXPOEBD.pdf','6563442432',5,1,'2023-09-03',2,'2023-09-03 16:04:26','2023-09-03 16:04:53'),(19,3,'2023-09-03','BiAUJWGO9F3Z1WR8YdoX5tzIKBkhgjRM7os9zYct.jpg',NULL,6,1,'2023-09-03',4,'2023-09-03 16:05:30','2023-09-03 16:07:18'),(26,6,'2023-09-04','OjprDNpJEZAguhZyXSGFrKBGc3T7NBPK795CgtVu.pdf',NULL,1,1,'2023-09-04',4,'2023-09-04 04:21:30','2023-09-04 04:22:08'),(27,6,'2023-09-05','m3XU5AmeeoKX8kNdGOpCNLs9xiTDRe6AXQNzuigR.png',NULL,2,1,'2023-09-04',2,'2023-09-04 04:23:06','2023-09-04 04:25:37'),(40,6,'2023-09-04','tXuyjUZB3LjA1Bh2w1sKJ5UfXDIH4hY8JRXoSYd4.pdf','1234213',3,0,NULL,NULL,'2023-09-04 09:51:55','2023-09-04 09:51:55');
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `imbal_jasas`
--

DROP TABLE IF EXISTS `imbal_jasas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `imbal_jasas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `plafond1` bigint unsigned NOT NULL,
  `plafond2` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imbal_jasas`
--

LOCK TABLES `imbal_jasas` WRITE;
/*!40000 ALTER TABLE `imbal_jasas` DISABLE KEYS */;
INSERT INTO `imbal_jasas` VALUES (1,0,10000000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(2,10000000,15000000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(3,15000000,20000000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(4,20000000,25000000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(5,25000000,30000000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(6,30000000,35000000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(7,35000000,40000000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(8,40000000,45000000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(9,45000000,50000000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(10,50000000,0,'2023-06-05 19:24:49','2023-06-05 19:24:49');
/*!40000 ALTER TABLE `imbal_jasas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kkb`
--

DROP TABLE IF EXISTS `kkb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kkb` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kredit_id` bigint unsigned NOT NULL,
  `tgl_ketersediaan_unit` date DEFAULT NULL,
  `id_tenor_imbal_jasa` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kkb_kredit_id_foreign` (`kredit_id`),
  KEY `kkb_id_tenor_imbal_jasa_foreign` (`id_tenor_imbal_jasa`),
  CONSTRAINT `kkb_id_tenor_imbal_jasa_foreign` FOREIGN KEY (`id_tenor_imbal_jasa`) REFERENCES `tenor_imbal_jasas` (`id`),
  CONSTRAINT `kkb_kredit_id_foreign` FOREIGN KEY (`kredit_id`) REFERENCES `kredits` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kkb`
--

LOCK TABLES `kkb` WRITE;
/*!40000 ALTER TABLE `kkb` DISABLE KEYS */;
INSERT INTO `kkb` VALUES (1,1,'2023-08-24',1,'2023-07-31 07:15:46','2023-08-22 08:09:51'),(2,2,'2023-09-07',1,'2023-07-31 07:15:46','2023-09-03 08:01:35'),(3,3,'2023-09-07',1,'2023-07-31 07:15:46','2023-09-03 15:49:35'),(4,4,'2023-09-07',1,'2023-08-10 07:43:00','2023-09-04 04:11:37'),(5,5,NULL,3,'2023-08-10 07:45:13','2023-08-10 07:45:13'),(6,6,'2023-09-07',2,'2023-08-10 07:46:02','2023-09-04 04:14:00');
/*!40000 ALTER TABLE `kkb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kredits`
--

DROP TABLE IF EXISTS `kredits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kredits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pengajuan_id` bigint unsigned NOT NULL,
  `kode_cabang` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kredits`
--

LOCK TABLES `kredits` WRITE;
/*!40000 ALTER TABLE `kredits` DISABLE KEYS */;
INSERT INTO `kredits` VALUES (1,70,'001','2023-07-31 07:15:46','2023-07-31 07:15:46'),(2,72,'001','2023-07-31 07:15:46','2023-07-31 07:15:46'),(3,94,'001','2023-07-31 07:15:46','2023-07-31 07:15:46'),(4,96,'001','2023-08-10 07:43:00','2023-08-10 07:43:00'),(5,97,'001','2023-08-10 07:45:13','2023-08-10 07:45:13'),(6,95,'001','2023-08-10 07:46:02','2023-08-10 07:46:02');
/*!40000 ALTER TABLE `kredits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_activities`
--

DROP TABLE IF EXISTS `log_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_activities_user_id_foreign` (`user_id`),
  CONSTRAINT `log_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=182 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_activities`
--

LOCK TABLES `log_activities` WRITE;
/*!40000 ALTER TABLE `log_activities` DISABLE KEYS */;
INSERT INTO `log_activities` VALUES (1,3,'Pengguna \' (01474)\' melakukan log out.','2023-07-31 06:16:43','2023-07-31 06:16:43'),(2,3,'Pengguna \'01474\' melakukan log in.','2023-07-31 06:18:12','2023-07-31 06:18:12'),(3,3,'Pengguna \' (01474)\' melakukan log out.','2023-07-31 07:04:04','2023-07-31 07:04:04'),(4,3,'Pengguna \'01474\' melakukan log in.','2023-07-31 07:17:04','2023-07-31 07:17:04'),(5,3,'Pengguna \' (01474)\' melakukan log out.','2023-07-31 11:13:12','2023-07-31 11:13:12'),(6,3,'Pengguna \'01474\' melakukan log in.','2023-08-02 09:53:13','2023-08-02 09:53:13'),(7,3,'Membuat target sebanayak 30 unit.','2023-08-02 09:53:28','2023-08-02 09:53:28'),(8,3,'Mengaktifkan target 30 unit.','2023-08-02 09:53:30','2023-08-02 09:53:30'),(9,3,'Menonaktifkan target yang bernilai 0.','2023-08-02 09:53:32','2023-08-02 09:53:32'),(10,3,'Mengaktifkan target 30 unit.','2023-08-02 09:53:34','2023-08-02 09:53:34'),(11,3,'Menonaktifkan target yang bernilai 0.','2023-08-02 09:53:34','2023-08-02 09:53:34'),(12,3,'Pengguna \' (01474)\' melakukan log out.','2023-08-02 09:53:41','2023-08-02 09:53:41'),(13,3,'Pengguna \'01474\' melakukan log in.','2023-08-05 03:29:21','2023-08-05 03:29:21'),(14,3,'Pengguna \'01474\' melakukan log in.','2023-08-05 03:33:10','2023-08-05 03:33:10'),(15,3,'Pengguna \' (01474)\' melakukan log out.','2023-08-05 03:34:42','2023-08-05 03:34:42'),(16,3,'Pengguna \'01474\' melakukan log in.','2023-08-05 03:34:53','2023-08-05 03:34:53'),(17,3,'Pengguna \' (01474)\' melakukan log out.','2023-08-05 03:35:39','2023-08-05 03:35:39'),(18,3,'Pengguna \' (01474)\' melakukan log out.','2023-08-05 03:35:41','2023-08-05 03:35:41'),(19,3,'Pengguna \'01474\' melakukan log in.','2023-08-05 03:35:46','2023-08-05 03:35:46'),(20,3,'Pengguna \'01474\' melakukan log in.','2023-08-05 03:35:47','2023-08-05 03:35:47'),(21,3,'Pengguna \' (01474)\' melakukan log out.','2023-08-05 03:37:23','2023-08-05 03:37:23'),(22,3,'Pengguna \'01474\' melakukan log in.','2023-08-05 03:39:16','2023-08-05 03:39:16'),(23,3,'Pengguna \' (01474)\' melakukan log out.','2023-08-05 03:41:03','2023-08-05 03:41:03'),(24,3,'Pengguna \'01474\' melakukan log in.','2023-08-05 03:52:32','2023-08-05 03:52:32'),(25,3,'Pengguna \'01474\' melakukan log in.','2023-08-07 04:31:49','2023-08-07 04:31:49'),(26,3,'Pengguna \'01474\' melakukan log in.','2023-08-10 06:38:00','2023-08-10 06:38:00'),(27,3,'Pengguna \' (01474)\' melakukan log out.','2023-08-10 06:40:35','2023-08-10 06:40:35'),(28,4,'Pengguna \'bjsc@mail.com\' melakukan log in.','2023-08-10 06:40:52','2023-08-10 06:40:52'),(29,4,'Pengguna \'bjsc@mail.com\' melakukan log in.','2023-08-10 06:43:17','2023-08-10 06:43:17'),(30,3,'Pengguna \'01474\' melakukan log in.','2023-08-10 06:46:58','2023-08-10 06:46:58'),(31,4,'Pengguna \'bjsc@mail.com\' melakukan log out.','2023-08-10 06:48:16','2023-08-10 06:48:16'),(32,4,'Pengguna \'bjsc@mail.com\' melakukan log in.','2023-08-10 06:53:44','2023-08-10 06:53:44'),(33,4,'Pengguna \'bjsc@mail.com\' melakukan log in.','2023-08-10 06:55:40','2023-08-10 06:55:40'),(34,4,'Pengguna \'bjsc@mail.com\' melakukan log in.','2023-08-10 06:56:48','2023-08-10 06:56:48'),(35,3,'Pengguna \'01474\' melakukan log in.','2023-08-10 07:03:52','2023-08-10 07:03:52'),(36,4,'Pengguna \'bjsc@mail.com\' melakukan log out.','2023-08-10 07:11:22','2023-08-10 07:11:22'),(37,3,'Pengguna \' (01474)\' melakukan log out.','2023-08-10 07:12:56','2023-08-10 07:12:56'),(38,3,'Pengguna \'01474\' melakukan log in.','2023-08-10 07:15:23','2023-08-10 07:15:23'),(39,4,'Pengguna \'bjsc@mail.com\' melakukan log in.','2023-08-10 07:16:49','2023-08-10 07:16:49'),(40,3,'Pengguna \' (01474)\' melakukan log out.','2023-08-10 07:26:10','2023-08-10 07:26:10'),(41,3,'Pengguna \'01474\' melakukan log in.','2023-08-10 07:26:50','2023-08-10 07:26:50'),(42,3,'Pengguna \' (01474)\' melakukan log out.','2023-08-10 07:53:30','2023-08-10 07:53:30'),(43,3,'Pengguna \'01474\' melakukan log in.','2023-08-10 08:02:35','2023-08-10 08:02:35'),(44,3,'Pengguna \'01474\' melakukan log in.','2023-08-10 08:18:00','2023-08-10 08:18:00'),(45,3,'Pengguna \'01474\' melakukan log in.','2023-08-14 07:16:29','2023-08-14 07:16:29'),(46,3,'Pengguna \'01474\' melakukan log in.','2023-08-15 05:58:45','2023-08-15 05:58:45'),(47,3,'Pengguna \'01474\' melakukan log in.','2023-08-17 07:41:51','2023-08-17 07:41:51'),(48,3,'Pengguna \' (01474)\' melakukan log out.','2023-08-17 07:46:10','2023-08-17 07:46:10'),(49,3,'Pengguna \'01474\' melakukan log in.','2023-08-17 08:00:54','2023-08-17 08:00:54'),(50,3,'Pengguna \'01474\' melakukan log in.','2023-08-19 03:54:58','2023-08-19 03:54:58'),(51,3,'Pengguna \'01474\' melakukan log in.','2023-08-19 09:25:23','2023-08-19 09:25:23'),(52,3,'Pengguna \'01474\' melakukan log in.','2023-08-21 01:31:52','2023-08-21 01:31:52'),(53,3,'Pengguna \'01474\' melakukan log in.','2023-08-21 03:19:08','2023-08-21 03:19:08'),(54,3,'Pengguna \'01474\' melakukan log in.','2023-08-21 04:24:40','2023-08-21 04:24:40'),(55,3,'Pengguna \'01474\' melakukan log in.','2023-08-21 07:00:05','2023-08-21 07:00:05'),(56,3,'Pengguna \'01474\' melakukan log in.','2023-08-21 07:12:32','2023-08-21 07:12:32'),(57,3,'Pengguna \'01474\' melakukan log in.','2023-08-21 07:57:40','2023-08-21 07:57:40'),(58,3,'Pengguna \'01474\' melakukan log in.','2023-08-21 08:51:02','2023-08-21 08:51:02'),(59,3,'Pengguna \' (01474)\' melakukan log out.','2023-08-21 09:45:23','2023-08-21 09:45:23'),(60,3,'Pengguna \' (01474)\' melakukan log out.','2023-08-21 10:10:53','2023-08-21 10:10:53'),(61,3,'Pengguna \'01474\' melakukan log in.','2023-08-21 10:14:23','2023-08-21 10:14:23'),(62,3,'Pengguna \'01474\' melakukan log in.','2023-08-22 03:43:03','2023-08-22 03:43:03'),(63,3,'Pengguna \'01474\' melakukan log in.','2023-08-22 06:50:19','2023-08-22 06:50:19'),(64,4,'Pengguna \'bjsc@mail.com\' melakukan log in.','2023-08-22 08:09:11','2023-08-22 08:09:11'),(65,4,'Pengguna  mengatur tanggal ketersediaan unit.','2023-08-22 08:09:51','2023-08-22 08:09:51'),(66,3,'Pengguna \' (01474)\' melakukan log out.','2023-08-22 08:10:10','2023-08-22 08:10:10'),(67,2,'Pengguna \'01497\' melakukan log in.','2023-08-22 08:10:30','2023-08-22 08:10:30'),(68,2,'Pengguna  mengunggah berkas bukti pembayaran.','2023-08-22 08:10:57','2023-08-22 08:10:57'),(69,4,'Pengguna  mengkonfirmasi berkas Bukti Pembayaran.','2023-08-22 08:11:33','2023-08-22 08:11:33'),(70,4,'Pengguna  mengatur tanggal penyerahan unit.','2023-08-22 08:12:29','2023-08-22 08:12:29'),(71,2,'Pengguna  mengkonfirmasi berkas Penyerahan Unit.','2023-08-22 08:13:02','2023-08-22 08:13:02'),(72,4,'Pengguna  mengunggah berkas.','2023-08-22 08:14:13','2023-08-22 08:14:13'),(73,2,'Pengguna  mengkonfirmasi berkas STNK.','2023-08-22 08:14:47','2023-08-22 08:14:47'),(74,4,'Pengguna  mengunggah berkas.','2023-08-22 08:15:32','2023-08-22 08:15:32'),(75,2,'Pengguna  mengkonfirmasi berkas BPKB.','2023-08-22 08:16:02','2023-08-22 08:16:02'),(76,2,'Pengguna  mengunggah berkas imbal jasa.','2023-08-22 08:16:53','2023-08-22 08:16:53'),(77,4,'Pengguna  mengkonfirmasi berkas imbal jasa.','2023-08-22 08:17:39','2023-08-22 08:17:39'),(78,2,'Pengguna \' (01497)\' melakukan log out.','2023-08-22 08:18:43','2023-08-22 08:18:43'),(79,3,'Pengguna \'01474\' melakukan log in.','2023-08-22 08:18:48','2023-08-22 08:18:48'),(80,3,'Pengguna \' (01474)\' melakukan log out.','2023-08-22 08:26:36','2023-08-22 08:26:36'),(81,3,'Pengguna \'01474\' melakukan log in.','2023-08-22 08:26:45','2023-08-22 08:26:45'),(82,4,'Pengguna \'bjsc@mail.com\' melakukan log out.','2023-08-22 08:28:19','2023-08-22 08:28:19'),(83,1,'Pengguna \'123456789012345678\' melakukan log in.','2023-08-22 08:28:50','2023-08-22 08:28:50'),(84,1,'Pengguna \' (123456789012345678)\' melakukan log out.','2023-08-22 08:29:08','2023-08-22 08:29:08'),(85,2,'Pengguna \'01497\' melakukan log in.','2023-08-22 08:29:27','2023-08-22 08:29:27'),(86,3,'Mengaktifkan target 30 unit.','2023-08-22 08:29:37','2023-08-22 08:29:37'),(87,3,'Pengguna \'01474\' melakukan log in.','2023-08-23 06:24:15','2023-08-23 06:24:15'),(88,3,'Pengguna \' (01474)\' melakukan log out.','2023-08-23 06:38:25','2023-08-23 06:38:25'),(89,4,'Pengguna \'bjsc@mail.com\' melakukan log in.','2023-08-23 06:38:59','2023-08-23 06:38:59'),(90,4,'Pengguna \'bjsc@mail.com\' melakukan log out.','2023-08-23 06:39:08','2023-08-23 06:39:08'),(91,2,'Pengguna \'01497\' melakukan log in.','2023-08-23 06:39:55','2023-08-23 06:39:55'),(92,1,'Pengguna \'123456789012345678\' melakukan log in.','2023-08-23 06:40:17','2023-08-23 06:40:17'),(93,1,'Pengguna \' (123456789012345678)\' melakukan log out.','2023-08-23 06:41:12','2023-08-23 06:41:12'),(94,2,'Pengguna \' (01497)\' melakukan log out.','2023-08-23 06:45:15','2023-08-23 06:45:15'),(95,3,'Pengguna \'01474\' melakukan log in.','2023-08-23 06:45:31','2023-08-23 06:45:31'),(96,3,'Pengguna \'01474\' melakukan log in.','2023-08-24 02:40:17','2023-08-24 02:40:17'),(97,3,'Pengguna \'01474\' melakukan log in.','2023-08-24 06:52:03','2023-08-24 06:52:03'),(98,3,'Pengguna \'01474\' melakukan log in.','2023-08-25 01:42:18','2023-08-25 01:42:18'),(99,3,'Pengguna \'01474\' melakukan log in.','2023-08-25 09:21:03','2023-08-25 09:21:03'),(100,3,'Pengguna \'01474\' melakukan log in.','2023-08-26 01:30:03','2023-08-26 01:30:03'),(101,3,'Pengguna \'01474\' melakukan log in.','2023-08-28 01:45:54','2023-08-28 01:45:54'),(102,3,'Pengguna \' (01474)\' melakukan log out.','2023-08-28 02:15:14','2023-08-28 02:15:14'),(103,3,'Pengguna \'01474\' melakukan log in.','2023-08-28 02:24:34','2023-08-28 02:24:34'),(104,3,'Pengguna \'01474\' melakukan log in.','2023-08-28 07:25:54','2023-08-28 07:25:54'),(105,3,'Pengguna \'01474\' melakukan log in.','2023-09-01 16:11:03','2023-09-01 16:11:03'),(106,3,'Pengguna \'01474\' melakukan log in.','2023-09-02 16:54:56','2023-09-02 16:54:56'),(107,3,'Membuat role tes.','2023-09-02 18:58:54','2023-09-02 18:58:54'),(108,3,'Membuat role asd.','2023-09-02 19:06:16','2023-09-02 19:06:16'),(109,3,'Membuat role asdww.','2023-09-02 19:06:38','2023-09-02 19:06:38'),(110,3,'Membuat role adswqd.','2023-09-02 19:07:41','2023-09-02 19:07:41'),(111,3,'Memperbarui role \'Role testing\' menjadi Role testing.','2023-09-02 19:29:05','2023-09-02 19:29:05'),(112,3,'Memperbarui role \'tes2\' menjadi tes2.','2023-09-02 19:29:12','2023-09-02 19:29:12'),(113,3,'Memperbarui role \'oke\' menjadi oke.','2023-09-02 19:29:18','2023-09-02 19:29:18'),(114,3,'Menghapus role oke.','2023-09-02 20:16:13','2023-09-02 20:16:13'),(115,3,'Menghapus role tes.','2023-09-02 20:18:30','2023-09-02 20:18:30'),(116,3,'Menghapus role tes2.','2023-09-02 20:20:19','2023-09-02 20:20:19'),(117,3,'Membuat role asd.','2023-09-02 20:21:18','2023-09-02 20:21:18'),(118,3,'Pengguna \'01474\' melakukan log in.','2023-09-03 03:02:12','2023-09-03 03:02:12'),(119,4,'Pengguna \'bjsc@mail.com\' melakukan log in.','2023-09-03 07:40:34','2023-09-03 07:40:34'),(120,4,'Pengguna \'bjsc@mail.com\' melakukan log out.','2023-09-03 07:52:42','2023-09-03 07:52:42'),(121,4,'Pengguna \'bjsc@mail.com\' melakukan log in.','2023-09-03 07:52:51','2023-09-03 07:52:51'),(122,4,'Pengguna  mengatur tanggal ketersediaan unit.','2023-09-03 08:01:35','2023-09-03 08:01:35'),(123,4,'Pengguna \'bjsc@mail.com\' melakukan log out.','2023-09-03 08:07:14','2023-09-03 08:07:14'),(124,2,'Pengguna  mengunggah berkas bukti pembayaran.','2023-09-03 08:40:41','2023-09-03 08:40:41'),(125,2,'Pengguna  mengunggah berkas bukti pembayaran.','2023-09-03 08:45:24','2023-09-03 08:45:24'),(126,4,'Pengguna  mengkonfirmasi berkas Bukti Pembayaran.','2023-09-03 09:14:37','2023-09-03 09:14:37'),(127,4,'Pengguna  mengatur tanggal penyerahan unit.','2023-09-03 09:30:37','2023-09-03 09:30:37'),(128,2,'Pengguna  mengkonfirmasi berkas Penyerahan Unit.','2023-09-03 09:54:08','2023-09-03 09:54:08'),(129,4,'Pengguna  mengunggah berkas.','2023-09-03 10:21:36','2023-09-03 10:21:36'),(130,2,'Pengguna  mengkonfirmasi berkas STNK.','2023-09-03 10:24:09','2023-09-03 10:24:09'),(131,4,'Pengguna  mengunggah berkas.','2023-09-03 10:24:28','2023-09-03 10:24:28'),(132,2,'Pengguna  mengkonfirmasi berkas BPKB.','2023-09-03 10:24:39','2023-09-03 10:24:39'),(133,2,'Pengguna  mengunggah berkas imbal jasa.','2023-09-03 10:35:39','2023-09-03 10:35:39'),(134,4,'Pengguna  mengkonfirmasi berkas imbal jasa.','2023-09-03 10:46:28','2023-09-03 10:46:28'),(135,2,'Pengguna \'bjsc@mail.com\' melakukan log in.','2023-09-03 15:35:43','2023-09-03 15:35:43'),(136,4,'Pengguna \'bjsc@mail.com\' melakukan log in.','2023-09-03 15:47:29','2023-09-03 15:47:29'),(137,4,'Pengguna  mengatur tanggal ketersediaan unit.','2023-09-03 15:49:35','2023-09-03 15:49:35'),(138,1,'Pengguna  mengunggah berkas bukti pembayaran.','2023-09-03 15:53:46','2023-09-03 15:53:46'),(139,4,'Pengguna  mengkonfirmasi berkas Bukti Pembayaran.','2023-09-03 15:57:12','2023-09-03 15:57:12'),(140,4,'Pengguna  mengatur tanggal penyerahan unit.','2023-09-03 15:59:24','2023-09-03 15:59:24'),(141,2,'Pengguna  mengkonfirmasi berkas Penyerahan Unit.','2023-09-03 16:01:54','2023-09-03 16:01:54'),(142,4,'Pengguna  mengunggah berkas.','2023-09-03 16:04:26','2023-09-03 16:04:26'),(143,2,'Pengguna  mengkonfirmasi berkas BPKB.','2023-09-03 16:04:53','2023-09-03 16:04:53'),(144,2,'Pengguna  mengunggah berkas imbal jasa.','2023-09-03 16:05:30','2023-09-03 16:05:30'),(145,4,'Pengguna  mengkonfirmasi berkas imbal jasa.','2023-09-03 16:07:18','2023-09-03 16:07:18'),(146,4,'Pengguna \'bjsc@mail.com\' melakukan log out.','2023-09-03 17:08:04','2023-09-03 17:08:04'),(147,4,'Pengguna \'bjsc@mail.com\' melakukan log in.','2023-09-03 18:27:48','2023-09-03 18:27:48'),(148,4,'Pengguna  mengatur tanggal ketersediaan unit.','2023-09-03 18:28:00','2023-09-03 18:28:00'),(149,2,'Pengguna  mengunggah berkas bukti pembayaran.','2023-09-03 18:28:47','2023-09-03 18:28:47'),(150,4,'Pengguna  mengkonfirmasi berkas Bukti Pembayaran.','2023-09-03 18:28:59','2023-09-03 18:28:59'),(151,4,'Pengguna  mengatur tanggal penyerahan unit.','2023-09-03 18:40:33','2023-09-03 18:40:33'),(152,2,'Pengguna  mengkonfirmasi berkas Penyerahan Unit.','2023-09-03 18:40:42','2023-09-03 18:40:42'),(153,4,'Pengguna  mengunggah berkas.','2023-09-03 18:47:52','2023-09-03 18:47:52'),(154,4,'Pengguna \'bjsc@mail.com\' melakukan log in.','2023-09-04 04:09:15','2023-09-04 04:09:15'),(155,4,'Pengguna  mengatur tanggal ketersediaan unit.','2023-09-04 04:11:37','2023-09-04 04:11:37'),(156,4,'Pengguna  mengatur tanggal ketersediaan unit.','2023-09-04 04:12:01','2023-09-04 04:12:01'),(157,4,'Pengguna  mengatur tanggal ketersediaan unit.','2023-09-04 04:14:00','2023-09-04 04:14:00'),(158,2,'Pengguna  mengunggah berkas bukti pembayaran.','2023-09-04 04:18:49','2023-09-04 04:18:49'),(159,2,'Pengguna  mengunggah berkas bukti pembayaran.','2023-09-04 04:21:30','2023-09-04 04:21:30'),(160,4,'Pengguna  mengkonfirmasi berkas Bukti Pembayaran.','2023-09-04 04:22:08','2023-09-04 04:22:08'),(161,4,'Pengguna  mengatur tanggal penyerahan unit.','2023-09-04 04:23:06','2023-09-04 04:23:06'),(162,2,'Pengguna  mengkonfirmasi berkas Penyerahan Unit.','2023-09-04 04:25:37','2023-09-04 04:25:37'),(163,4,'Pengguna  mengunggah berkas.','2023-09-04 04:34:49','2023-09-04 04:34:49'),(164,2,'Pengguna  mengkonfirmasi berkas undifined.','2023-09-04 05:09:31','2023-09-04 05:09:31'),(165,2,'Pengguna  mengkonfirmasi berkas undifined.','2023-09-04 05:09:52','2023-09-04 05:09:52'),(166,2,'Pengguna  mengkonfirmasi berkas undifined.','2023-09-04 05:10:18','2023-09-04 05:10:18'),(167,2,'Pengguna  mengkonfirmasi berkas STNK.','2023-09-04 05:17:29','2023-09-04 05:17:29'),(168,2,'Pengguna  mengkonfirmasi berkas BPKB.','2023-09-04 05:30:20','2023-09-04 05:30:20'),(169,4,'Pengguna  mengunggah berkas.','2023-09-04 05:32:07','2023-09-04 05:32:07'),(170,2,'Pengguna  mengkonfirmasi berkas Polis.','2023-09-04 05:32:22','2023-09-04 05:32:22'),(171,2,'Pengguna  mengunggah berkas imbal jasa.','2023-09-04 05:32:30','2023-09-04 05:32:30'),(172,4,'Pengguna  mengkonfirmasi berkas imbal jasa.','2023-09-04 05:32:43','2023-09-04 05:32:43'),(173,4,'Pengguna \'bjsc@mail.com\' melakukan log in.','2023-09-04 09:17:52','2023-09-04 09:17:52'),(174,4,'Pengguna  mengunggah berkas.','2023-09-04 09:18:09','2023-09-04 09:18:09'),(175,2,'Pengguna  mengkonfirmasi berkas STNK.','2023-09-04 09:31:31','2023-09-04 09:31:31'),(176,2,'Pengguna  mengkonfirmasi berkas BPKB.','2023-09-04 09:34:10','2023-09-04 09:34:10'),(177,2,'Pengguna  mengkonfirmasi berkas BPKB.','2023-09-04 09:38:47','2023-09-04 09:38:47'),(178,4,'Pengguna  mengunggah berkas.','2023-09-04 09:42:28','2023-09-04 09:42:28'),(179,2,'Pengguna  mengunggah berkas imbal jasa.','2023-09-04 09:51:20','2023-09-04 09:51:20'),(180,4,'Pengguna  mengunggah berkas.','2023-09-04 09:51:55','2023-09-04 09:51:55'),(181,4,'Pengguna \'bjsc@mail.com\' melakukan log in.','2023-09-05 02:35:31','2023-09-05 02:35:31');
/*!40000 ALTER TABLE `log_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_04_28_130124_create_roles_table',1),(2,'2014_04_28_130752_create_vendors_table',1),(3,'2014_10_12_000000_create_users_table',1),(4,'2014_10_12_100000_create_password_resets_table',1),(5,'2019_08_19_000000_create_failed_jobs_table',1),(6,'2019_12_14_000001_create_personal_access_tokens_table',1),(7,'2023_04_28_130247_create_actions_table',1),(8,'2023_04_28_130354_create_permissions_table',1),(9,'2023_04_28_130518_create_log_activities_table',1),(10,'2023_04_28_131118_create_document_categories_table',1),(11,'2023_04_28_131237_create_kredits_table',1),(12,'2023_04_28_131328_create_documents_table',1),(13,'2023_04_28_131612_create_kkb_table',1),(14,'2023_04_28_131725_create_notification_templates_table',1),(15,'2023_04_28_131848_create_notifications_table',1),(16,'2023_05_06_142304_add_cabang_id_to_vendors_table',1),(17,'2023_05_09_101235_rename_field_tgl_ketersediaan_unit_on_kkb_table',1),(18,'2023_05_09_102000_change_tgl_ketersediaan_unit_to_nullable_on_kkb_table',2),(19,'2023_05_09_102103_change_imbal_jasa_to_nullable_on_kkb_table',2),(20,'2023_05_11_182301_add_is_confirm_to_documents_table',2),(21,'2023_05_11_191020_add_confirm_by_to_documents_table',2),(22,'2023_05_12_195009_create_imbal_jasas_table',2),(23,'2023_05_13_061820_create_tenor_imbal_jasas_table',2),(24,'2023_05_15_161414_add_id_tenor_imbal_jasa_on_kkb_table',2),(25,'2023_05_16_111240_create_target_table',2),(26,'2023_05_20_195641_add_total_unit_to_target_table',2),(27,'2023_05_23_124859_add_all_role_to_notification_templates',2),(28,'2023_05_23_125038_modify_notifications_table',2),(29,'2023_05_23_161126_drop_role_id_foreign_on_notification_templates_table',2),(30,'2023_05_23_161152_change_role_id_on_notification_templates_table',2),(31,'2023_05_23_214950_add_foreign_key_to_notifications_table',2),(32,'2023_05_24_123130_add_kredit_id_to_notifications_table',2),(33,'2023_05_24_160444_add_kode_cabang_to_users_table',2),(34,'2023_06_08_184401_add_first_login_users',3),(35,'2023_08_14_140312_create_mst_file_dictionary_table',4),(36,'2023_08_14_141212_create_mst_file_content_dictionary_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mst_file_content_dictionary`
--

DROP TABLE IF EXISTS `mst_file_content_dictionary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_file_content_dictionary` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `file_dictionary_id` bigint unsigned NOT NULL,
  `field` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from` smallint unsigned NOT NULL,
  `to` smallint unsigned NOT NULL,
  `length` tinyint unsigned NOT NULL,
  `description` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mst_file_content_dictionary_file_dictionary_id_foreign` (`file_dictionary_id`),
  CONSTRAINT `mst_file_content_dictionary_file_dictionary_id_foreign` FOREIGN KEY (`file_dictionary_id`) REFERENCES `mst_file_dictionary` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=311 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_file_content_dictionary`
--

LOCK TABLES `mst_file_content_dictionary` WRITE;
/*!40000 ALTER TABLE `mst_file_content_dictionary` DISABLE KEYS */;
INSERT INTO `mst_file_content_dictionary` VALUES (14,18,'WISTAT',1,1,1,'Status record','2023-08-15 07:40:08','2023-08-15 07:40:08'),(15,18,'WICODE',2,3,2,'Wilayah','2023-08-15 07:40:08','2023-08-15 07:40:08'),(16,18,'WINAME',4,33,30,'Nama wilayah','2023-08-15 07:40:08','2023-08-15 07:40:08'),(17,18,'WIDTLC',34,41,8,'Tanggal diubah','2023-08-15 07:40:08','2023-08-15 07:40:08'),(18,19,'L0STAT',1,1,1,'Status record','2023-08-15 07:52:00','2023-08-15 07:52:00'),(19,19,'L0STAD',2,2,1,'Status Data','2023-08-15 07:52:00','2023-08-15 07:52:00'),(20,19,'L0BRCA',3,4,2,'Wilayah','2023-08-15 07:52:00','2023-08-15 07:52:00'),(21,19,'L0BRCD',5,7,3,'Branch','2023-08-15 07:52:00','2023-08-15 07:52:00'),(22,19,'L0CSNO',8,15,8,'Customer code','2023-08-15 07:52:00','2023-08-15 07:52:00'),(23,19,'L0FCTY',16,18,3,'Facility Type','2023-08-15 07:52:00','2023-08-15 07:52:00'),(24,19,'L0FCSQ',19,20,2,'Fac. Seq. No','2023-08-15 07:52:00','2023-08-15 07:52:00'),(25,19,'L0FISN',21,28,8,'Cust.No  Facilitas','2023-08-15 07:52:00','2023-08-15 07:52:00'),(26,19,'L0FITY',29,31,3,'Fac Type Facilitas','2023-08-15 07:52:00','2023-08-15 07:52:00'),(27,19,'L0FISQ',32,33,2,'Fac Seqn Facilitas','2023-08-15 07:52:00','2023-08-15 07:52:00'),(28,19,'L0LNCS',34,41,8,'Loan Cust No','2023-08-15 07:52:00','2023-08-15 07:52:00'),(29,19,'L0CSPR',42,49,8,'Cust.Parent No','2023-08-15 07:52:00','2023-08-15 07:52:00'),(30,19,'L0LNNO',50,57,8,'Loan Number','2023-08-15 07:52:00','2023-08-15 07:52:00'),(31,19,'L0LNPR',58,58,1,'Loan Processing','2023-08-15 07:52:00','2023-08-15 07:52:00'),(32,19,'L0LNTY',59,63,5,'Loan Type','2023-08-15 07:52:00','2023-08-15 07:52:00'),(33,19,'L0CYCD',64,66,3,'Loan Ccy Code','2023-08-15 07:52:00','2023-08-15 07:52:00'),(34,19,'L0NAME',67,96,30,'Loan Holder','2023-08-15 07:52:00','2023-08-15 07:52:00'),(35,19,'L0TNGI',97,97,1,'Tangible/ Intangible','2023-08-15 07:52:00','2023-08-15 07:52:00'),(36,19,'L0ALTN',98,98,1,'Alternate N/A','2023-08-15 07:52:00','2023-08-15 07:52:00'),(37,19,'L0ECON',99,102,4,'Economical Sector Code','2023-08-15 07:52:00','2023-08-15 07:52:00'),(38,19,'L0INDS',103,106,4,'Industry Code','2023-08-15 07:52:00','2023-08-15 07:52:00'),(39,19,'L0LOCL',107,110,4,'Location Code','2023-08-15 07:52:00','2023-08-15 07:52:00'),(40,19,'L0TYUS',111,112,2,'Type of Use','2023-08-15 07:52:00','2023-08-15 07:52:00'),(41,19,'L0COLE',113,113,1,'Collectibility External','2023-08-15 07:52:00','2023-08-15 07:52:00'),(42,19,'L0COLI',114,114,1,'Collectibility Internal','2023-08-15 07:52:00','2023-08-15 07:52:00'),(43,19,'L0COLS',115,115,1,'Collectibility System','2023-08-15 07:52:00','2023-08-15 07:52:00'),(44,19,'L0OWNC',116,118,3,'Owner Clasf.','2023-08-15 07:52:00','2023-08-15 07:52:00'),(45,19,'L0ACOF',119,120,2,'A/O code','2023-08-15 07:52:00','2023-08-15 07:52:00'),(46,19,'L0NARR',121,150,30,'Narrative','2023-08-15 07:52:00','2023-08-15 07:52:00'),(47,19,'L0STDT',151,158,8,'Start Date','2023-08-15 07:52:00','2023-08-15 07:52:00'),(48,19,'L0MTDT',159,166,8,'Tanggal','2023-08-15 07:52:00','2023-08-15 07:52:00'),(49,19,'L0FBRA',167,168,2,'From Area Code','2023-08-15 07:52:00','2023-08-15 07:52:00'),(50,19,'L0FBRC',169,171,3,'From Branch Code','2023-08-15 07:52:00','2023-08-15 07:52:00'),(51,19,'L0FLKU',172,172,1,'KUK/Non KUK','2023-08-15 07:52:00','2023-08-15 07:52:00'),(52,19,'L0IAFL',173,173,1,'Stop Int.Accr /Amrt','2023-08-15 07:52:00','2023-08-15 07:52:00'),(53,19,'L0PAFL',174,174,1,'Stop Penalty Accr.','2023-08-15 07:52:00','2023-08-15 07:52:00'),(54,19,'L0BWFL',175,175,1,'Write Off','2023-08-15 07:52:00','2023-08-15 07:52:00'),(55,19,'L0DURA',176,180,5,'Duration','2023-08-15 07:52:00','2023-08-15 07:52:00'),(56,19,'L0CALM',181,183,3,'Calc.Meth (12/360/365-6)','2023-08-15 07:52:00','2023-08-15 07:52:00'),(57,19,'L0BSCD',184,185,2,'Base Rate code','2023-08-15 07:52:00','2023-08-15 07:52:00'),(58,19,'L0SPRT',186,192,7,'Spread /Rate','2023-08-15 07:52:00','2023-08-15 07:52:00'),(59,19,'L0CIRT',193,199,7,'Total/Current Int.Rate','2023-08-15 07:52:00','2023-08-15 07:52:00'),(60,19,'L0DIRT',200,206,7,'Dummy Int. Rate','2023-08-15 07:52:00','2023-08-15 07:52:00'),(61,19,'L0ADRT',207,213,7,'Int Rate  For Advise','2023-08-15 07:52:00','2023-08-15 07:52:00'),(62,19,'L0MRRT',214,220,7,'Margin  Rate','2023-08-15 07:52:00','2023-08-15 07:52:00'),(63,19,'L0MRFL',221,221,1,'Margin Indicator','2023-08-15 07:52:00','2023-08-15 07:52:00'),(64,19,'L0LNRT',222,232,11,'Loan Exch Rate','2023-08-15 07:52:00','2023-08-15 07:52:00'),(65,19,'L0FCRT',233,243,11,'Fac. Exch Rate','2023-08-15 07:52:00','2023-08-15 07:52:00'),(66,19,'L0FCMT',244,258,15,'Facility Amount - Orig','2023-08-15 07:52:00','2023-08-15 07:52:00'),(67,19,'L0SSTL',259,268,10,'DrawDwn/Start Settl. A/C','2023-08-15 07:52:00','2023-08-15 07:52:00'),(68,19,'L0RSTL',269,278,10,'Repay/Princp. Settl. A/C','2023-08-15 07:52:00','2023-08-15 07:52:00'),(69,19,'L0ISTL',279,288,10,'Interest Settl. A/C','2023-08-15 07:52:00','2023-08-15 07:52:00'),(70,19,'L0RASF',289,289,1,'Repay. Auto Sett.Flag','2023-08-15 07:52:00','2023-08-15 07:52:00'),(71,19,'L0IASF',290,290,1,'Int. Auto Sett.Flag','2023-08-15 07:52:00','2023-08-15 07:52:00'),(72,19,'L0RPTY',291,291,1,'Repayment Type','2023-08-15 07:52:00','2023-08-15 07:52:00'),(73,19,'L0RPDT',292,299,8,'Repayment Date','2023-08-15 07:52:00','2023-08-15 07:52:00'),(74,19,'L0RPFR',300,300,1,'Frequency','2023-08-15 07:52:00','2023-08-15 07:52:00'),(75,19,'L0RPDY',301,302,2,'Repayment Day No','2023-08-15 07:52:00','2023-08-15 07:52:00'),(76,19,'L0RPAM',303,317,15,'Repayment Amount','2023-08-15 07:52:00','2023-08-15 07:52:00'),(77,19,'L0STPD',318,325,8,'StartDate Pric.Deduction','2023-08-15 07:52:00','2023-08-15 07:52:00'),(78,19,'L0NXRP',326,333,8,'Next Repayment Date','2023-08-15 07:52:00','2023-08-15 07:52:00'),(79,19,'L0LSRP',334,341,8,'Last Repayment Date','2023-08-15 07:52:00','2023-08-15 07:52:00'),(80,19,'L0RSFL',342,342,1,'Repayment Sched.Flag','2023-08-15 07:52:00','2023-08-15 07:52:00'),(81,19,'L0IPDT',343,350,8,'Int.Payment Date','2023-08-15 07:52:00','2023-08-15 07:52:00'),(82,19,'L0IPFR',351,351,1,'Int.Payment Frequency','2023-08-15 07:52:00','2023-08-15 07:52:00'),(83,19,'L0IPDY',352,353,2,'Int.Payment Day No','2023-08-15 07:52:00','2023-08-15 07:52:00'),(84,19,'L0IPAM',354,368,15,'Interest Amount','2023-08-15 07:52:00','2023-08-15 07:52:00'),(85,19,'L0NXIP',369,376,8,'Next Int.Payment Date','2023-08-15 07:52:00','2023-08-15 07:52:00'),(86,19,'L0LSIP',377,384,8,'Last Int.Payment Date','2023-08-15 07:52:00','2023-08-15 07:52:00'),(87,19,'L0IPFL',385,385,1,'Int.Pay. Revolv. Flag','2023-08-15 07:52:00','2023-08-15 07:52:00'),(88,19,'L0PNRT',386,392,7,'Penalty Rate','2023-08-15 07:52:00','2023-08-15 07:52:00'),(89,19,'L0MPMT',393,407,15,'Minimum Penalty Amount','2023-08-15 07:52:00','2023-08-15 07:52:00'),(90,19,'L0PAMO',408,422,15,'Principle Amount Orig.','2023-08-15 07:52:00','2023-08-15 07:52:00'),(91,19,'L0TDMT',423,437,15,'Total Discount Int.Amt.','2023-08-15 07:52:00','2023-08-15 07:52:00'),(92,19,'L0IBVD',438,445,8,'Int.Back Value Date','2023-08-15 07:52:00','2023-08-15 07:52:00'),(93,19,'L0PBVD',446,453,8,'Princ.Back Value Date','2023-08-15 07:52:00','2023-08-15 07:52:00'),(94,19,'L0DVFL',454,454,1,'Div Factor for Accrual','2023-08-15 07:52:00','2023-08-15 07:52:00'),(95,19,'L0SADT',455,462,8,'Stop Int Acc.Date','2023-08-15 07:52:00','2023-08-15 07:52:00'),(96,19,'L0AJMT',463,477,15,'Accrue Adj Manual','2023-08-15 07:52:00','2023-08-15 07:52:00'),(97,19,'L0DAMT',478,492,15,'Accrue Adj by System','2023-08-15 07:52:00','2023-08-15 07:52:00'),(98,19,'L0ACDT',493,500,8,'Last Date Accrued','2023-08-15 07:52:00','2023-08-15 07:52:00'),(99,19,'L0ACMT',501,515,15,'Next Amt.Posted Org Ccy','2023-08-15 07:52:00','2023-08-15 07:52:00'),(100,19,'L0PADT',516,523,8,'Last Date Acc.Posted','2023-08-15 07:52:00','2023-08-15 07:52:00'),(101,19,'L0PAMT',524,538,15,'Next Amt.Cap. Org Ccy','2023-08-15 07:52:00','2023-08-15 07:52:00'),(102,19,'L0CADT',539,546,8,'Last Date Capitalized','2023-08-15 07:52:00','2023-08-15 07:52:00'),(103,19,'L0CAMT',547,561,15,'Tot.Capitalized Org Ccy','2023-08-15 07:52:00','2023-08-15 07:52:00'),(104,19,'L0ACTR',562,563,2,'Accrual Days Counter','2023-08-15 07:52:00','2023-08-15 07:52:00'),(105,19,'L0DUPI',564,578,15,'Due Principle Amt','2023-08-15 07:52:00','2023-08-15 07:52:00'),(106,19,'L0DUIN',579,593,15,'Due Interest Amt','2023-08-15 07:52:00','2023-08-15 07:52:00'),(107,19,'L0DUPN',594,608,15,'Due Penalty Amt','2023-08-15 07:52:00','2023-08-15 07:52:00'),(108,19,'L0PAPI',609,623,15,'Principle Paid Amount','2023-08-15 07:52:00','2023-08-15 07:52:00'),(109,19,'L0PAIN',624,638,15,'Interest Paid Amount','2023-08-15 07:52:00','2023-08-15 07:52:00'),(110,19,'L0PAPT',639,653,15,'Penalty Paid Amount','2023-08-15 07:52:00','2023-08-15 07:52:00'),(111,19,'L0PAFE',654,668,15,'Fee Paid Amount','2023-08-15 07:52:00','2023-08-15 07:52:00'),(112,19,'L0WOPI',669,683,15,'Write Off Principle','2023-08-15 07:52:00','2023-08-15 07:52:00'),(113,19,'L0WOIN',684,698,15,'Write Off Interest','2023-08-15 07:52:00','2023-08-15 07:52:00'),(114,19,'L0WOPN',699,713,15,'Write Off Penalty','2023-08-15 07:52:00','2023-08-15 07:52:00'),(115,19,'L0APPI',714,728,15,'Principle Paid Unaut.Amt','2023-08-15 07:52:00','2023-08-15 07:52:00'),(116,19,'L0APIN',729,743,15,'Interest Paid Unauth.Amt','2023-08-15 07:52:00','2023-08-15 07:52:00'),(117,19,'L0APPT',744,758,15,'Penalty Paid Unauth.Amt.','2023-08-15 07:52:00','2023-08-15 07:52:00'),(118,19,'L0APFE',759,773,15,'Fee Paid Unauth.Amount','2023-08-15 07:52:00','2023-08-15 07:52:00'),(119,19,'L0AWPI',774,788,15,'Unaut.Write Of Principle','2023-08-15 07:52:00','2023-08-15 07:52:00'),(120,19,'L0AWIN',789,803,15,'Unaut.Write Off Interest','2023-08-15 07:52:00','2023-08-15 07:52:00'),(121,19,'L0AWPN',804,818,15,'Unaut.Write Off Penalty','2023-08-15 07:52:00','2023-08-15 07:52:00'),(122,19,'L0PDPI',819,826,8,'Pri/Repay Due Str. Date','2023-08-15 07:52:00','2023-08-15 07:52:00'),(123,19,'L0SDIN',827,834,8,'Interest Due Start Date','2023-08-15 07:52:00','2023-08-15 07:52:00'),(124,19,'L0LPPI',835,842,8,'Last Principle Pay.Date','2023-08-15 07:52:00','2023-08-15 07:52:00'),(125,19,'L0LPIN',843,850,8,'Last Interest Pay. Date','2023-08-15 07:52:00','2023-08-15 07:52:00'),(126,19,'L0LPPT',851,858,8,'Last Penalty Pay. Date','2023-08-15 07:52:00','2023-08-15 07:52:00'),(127,19,'L0LPFE',859,866,8,'Last Fee Payment Date','2023-08-15 07:52:00','2023-08-15 07:52:00'),(128,19,'L0WPDT',867,874,8,'Date Last Write Off Pri.','2023-08-15 07:52:00','2023-08-15 07:52:00'),(129,19,'L0WIDT',875,882,8,'Date Last Write Off Int.','2023-08-15 07:52:00','2023-08-15 07:52:00'),(130,19,'L0SDDT',883,890,8,'Start Calc Penalty Date','2023-08-15 07:52:00','2023-08-15 07:52:00'),(131,19,'L0WNDT',891,898,8,'Date Last Write Off Pnt.','2023-08-15 07:52:00','2023-08-15 07:52:00'),(132,19,'L0NOPN',899,902,4,'No. of Penalty','2023-08-15 07:52:00','2023-08-15 07:52:00'),(133,19,'L0OVFL',903,905,3,'Override Flag','2023-08-15 07:52:00','2023-08-15 07:52:00'),(134,19,'L0OVUS',906,915,10,'Override by','2023-08-15 07:52:00','2023-08-15 07:52:00'),(135,19,'L0FJDE',916,923,8,'1st Job  Date Entry','2023-08-15 07:52:00','2023-08-15 07:52:00'),(136,19,'L0USID',924,933,10,'User ID','2023-08-15 07:52:00','2023-08-15 07:52:00'),(137,19,'L0DEPT',934,935,2,'Department Code','2023-08-15 07:52:00','2023-08-15 07:52:00'),(138,19,'L0LSDA',936,943,8,'Last Sys Date Amend','2023-08-15 07:52:00','2023-08-15 07:52:00'),(139,19,'L0LJDA',944,951,8,'Last Job Date Amend','2023-08-15 07:52:00','2023-08-15 07:52:00'),(140,19,'L0LSTA',952,957,6,'Last Time Amend','2023-08-15 07:52:00','2023-08-15 07:52:00'),(141,19,'L0AUUS',958,967,10,'Authorize By','2023-08-15 07:52:00','2023-08-15 07:52:00'),(142,19,'L0WSID',968,977,10,'Display ID','2023-08-15 07:52:00','2023-08-15 07:52:00'),(147,14,'CYSTAT',1,1,1,'Status record','2023-08-15 08:13:42','2023-08-15 08:13:42'),(148,14,'CYCODE',2,4,3,'Currency Code','2023-08-15 08:13:42','2023-08-15 08:13:42'),(149,14,'CYNAME',5,34,30,'Currency Name','2023-08-15 08:13:42','2023-08-15 08:13:42'),(150,14,'CYDTLC',35,42,8,'Tanggal diubah','2023-08-15 08:13:42','2023-08-15 08:13:42'),(151,14,'CYDECI',43,43,1,'Decimal Point','2023-08-15 08:13:42','2023-08-15 08:13:42'),(152,20,'BITYPE',1,1,1,'2-Loan,3-Non PRK CER','2023-08-17 07:42:27','2023-08-17 07:42:27'),(153,20,'BITNGI',2,2,1,'Direct/Indirect','2023-08-17 07:42:27','2023-08-17 07:42:27'),(154,20,'BIBRCO',4,5,3,'Branch Code','2023-08-17 07:42:27','2023-08-17 07:42:27'),(155,20,'BICYCO',6,8,3,'Currency Kredit','2023-08-17 07:42:27','2023-08-17 07:42:27'),(156,20,'BICYFL',9,11,3,'Currency Fasilitas','2023-08-17 07:42:27','2023-08-17 07:42:27'),(157,20,'BICSNO',12,19,8,'Cust.Fac.No','2023-08-17 07:42:27','2023-08-17 07:42:27'),(158,20,'BIFCTY',20,22,3,'Fac.Type','2023-08-17 07:42:27','2023-08-17 07:42:27'),(159,20,'BIFCSQ',23,24,2,'Fac.Seq','2023-08-17 07:42:27','2023-08-17 07:42:27'),(160,20,'BICODE',25,39,15,'No.Rekening','2023-08-17 07:42:27','2023-08-17 07:42:27'),(161,20,'BILNTY',40,44,5,'Loan Type','2023-08-17 07:42:27','2023-08-17 07:42:27'),(162,20,'BINAME',45,74,30,'Nama Debitur','2023-08-17 07:42:27','2023-08-17 07:42:27'),(163,20,'BIREFN',75,89,15,'Fac.Ref','2023-08-17 07:42:27','2023-08-17 07:42:27'),(164,20,'BINARR',90,119,30,'Fac.Narrative','2023-08-17 07:42:27','2023-08-17 07:42:27'),(165,20,'BINMON',120,122,3,'Month','2023-08-17 07:42:27','2023-08-17 07:42:27'),(166,20,'BINDAY',123,124,2,'Day','2023-08-17 07:42:27','2023-08-17 07:42:27'),(167,20,'BIINRT',125,131,7,'Interest Rate','2023-08-17 07:42:27','2023-08-17 07:42:27'),(168,20,'BIINRR',132,138,7,'Interest Rate Low','2023-08-17 07:42:27','2023-08-17 07:42:27'),(169,20,'BIFCMT',139,153,15,'Plafond','2023-08-17 07:42:27','2023-08-17 07:42:27'),(170,20,'BIPLAF',154,168,15,'Baki Debet','2023-08-17 07:42:27','2023-08-17 07:42:27'),(171,20,'BIAVMT',169,183,15,'Longgar Tarik','2023-08-17 07:42:27','2023-08-17 07:42:27'),(172,20,'BISIFT',184,184,1,'Orient.Pengg','2023-08-17 07:42:27','2023-08-17 07:42:27'),(173,20,'BITYUS',185,186,2,'Type of Use','2023-08-17 07:42:27','2023-08-17 07:42:27'),(174,20,'BICOLC',187,187,1,'Collectibility','2023-08-17 07:42:27','2023-08-17 07:42:27'),(175,20,'BIOWNC',188,190,3,'Owner Code','2023-08-17 07:42:27','2023-08-17 07:42:27'),(176,20,'BIGOKR',191,192,2,'Gol.Kredit','2023-08-17 07:42:27','2023-08-17 07:42:27'),(177,20,'BIECON',193,196,4,'Economic Sect.','2023-08-17 07:42:27','2023-08-17 07:42:27'),(178,20,'BILOCL',197,200,4,'Location','2023-08-17 07:42:27','2023-08-17 07:42:27'),(179,20,'BIGOPJ',201,203,3,'Gol.Penjamin','2023-08-17 07:42:27','2023-08-17 07:42:27'),(180,20,'BIBADJ',204,207,4,'Bagian Dijamin','2023-08-17 07:42:27','2023-08-17 07:42:27'),(181,20,'BIHUBU',208,208,1,'Hub.Bank','2023-08-17 07:42:27','2023-08-17 07:42:27'),(182,20,'BIPPAP',209,223,15,'PPAP','2023-08-17 07:42:27','2023-08-17 07:42:27'),(183,20,'BIBULA',224,238,15,'Bulan Lalu','2023-08-17 07:42:27','2023-08-17 07:42:27'),(184,20,'BIANGU',239,253,15,'Agunan','2023-08-17 07:42:27','2023-08-17 07:42:27'),(185,20,'BISIF1',254,255,2,'Sifat','2023-08-17 07:42:27','2023-08-17 07:42:27'),(186,20,'BIMYST',256,261,6,'Start Date','2023-08-17 07:42:27','2023-08-17 07:42:27'),(187,20,'BIMYDU',262,267,6,'End Date','2023-08-17 07:42:27','2023-08-17 07:42:27'),(188,20,'BIODDT',268,275,8,'OD Date','2023-08-17 07:42:27','2023-08-17 07:42:27'),(189,20,'BIFLAG',276,276,1,'Flag Grouping','2023-08-17 07:42:27','2023-08-17 07:42:27'),(190,21,'PUSTAT',1,8,1,'Status record','2023-08-17 08:02:00','2023-08-17 08:02:00'),(191,21,'PUCODE',9,16,8,'Customer code','2023-08-17 08:02:00','2023-08-17 08:02:00'),(192,21,'PUNAME',17,46,30,'Customer Name','2023-08-17 08:02:00','2023-08-17 08:02:00'),(193,21,'PURSKF',47,48,2,'Risk Factor','2023-08-17 08:02:00','2023-08-17 08:02:00'),(194,21,'PURSNM',49,78,30,'Deskripsi Risk Factor','2023-08-17 08:02:00','2023-08-17 08:02:00'),(195,21,'PUDML1',79,108,30,'Domisili 1','2023-08-17 08:02:00','2023-08-17 08:02:00'),(196,21,'PUDML2',109,138,30,'Domisili 2','2023-08-17 08:02:00','2023-08-17 08:02:00'),(197,21,'PUDML3',139,168,30,'Domisili 3','2023-08-17 08:02:00','2023-08-17 08:02:00'),(198,21,'PUDML4',169,198,30,'Domisili 4','2023-08-17 08:02:00','2023-08-17 08:02:00'),(199,21,'PUPPTF',199,200,2,'Flag PPT','2023-08-17 08:02:00','2023-08-17 08:02:00'),(200,21,'PUREDF',201,202,2,'Red Flag','2023-08-17 08:02:00','2023-08-17 08:02:00'),(201,21,'PUKIMS',203,232,30,'No.KIMS/KITAP','2023-08-17 08:02:00','2023-08-17 08:02:00'),(202,21,'PUJOBS',233,236,4,'Kode Pekerjaan','2023-08-17 08:02:00','2023-08-17 08:02:00'),(203,21,'PUSUDN',237,240,4,'Sumber Dana','2023-08-17 08:02:00','2023-08-17 08:02:00'),(204,21,'PUSUDS',241,270,30,'Deskripsi Sumber Dana','2023-08-17 08:02:00','2023-08-17 08:02:00'),(205,21,'PUTUDN',271,274,4,'Tujuan Penggunaan Dana','2023-08-17 08:02:00','2023-08-17 08:02:00'),(206,21,'PUTUDS',275,304,30,'Deskripsi Tujuan Dana','2023-08-17 08:02:00','2023-08-17 08:02:00'),(207,21,'PURKRJ',305,464,160,'Job Type Relation','2023-08-17 08:02:00','2023-08-17 08:02:00'),(208,21,'PUNM01',465,494,30,'Nama Pengurus 1','2023-08-17 08:02:00','2023-08-17 08:02:00'),(209,21,'PUNM02',495,524,30,'Nama Pengurus 2','2023-08-17 08:02:00','2023-08-17 08:02:00'),(210,21,'PUNM03',525,554,30,'Nama Pengurus 3','2023-08-17 08:02:00','2023-08-17 08:02:00'),(211,21,'PUNM04',555,584,30,'Nama Pengurus 4','2023-08-17 08:02:00','2023-08-17 08:02:00'),(212,21,'PUNM05',585,614,30,'Nama Pengurus 5','2023-08-17 08:02:00','2023-08-17 08:02:00'),(213,21,'PUCD01',615,616,2,'Kode Pengurus 1','2023-08-17 08:02:00','2023-08-17 08:02:00'),(214,21,'PUCD02',617,618,2,'Kode Pengurus 2','2023-08-17 08:02:00','2023-08-17 08:02:00'),(215,21,'PUCD03',619,620,2,'Kode Pengurus 3','2023-08-17 08:02:00','2023-08-17 08:02:00'),(216,21,'PUCD04',621,622,2,'Kode Pengurus 4','2023-08-17 08:02:00','2023-08-17 08:02:00'),(217,21,'PUCD05',623,624,2,'Kode Pengurus 5','2023-08-17 08:02:00','2023-08-17 08:02:00'),(218,21,'PUBOWN',625,625,1,'Beneficiary Owner','2023-08-17 08:02:00','2023-08-17 08:02:00'),(219,21,'PONAME',626,655,30,'Beneficiary Owner Name','2023-08-17 08:02:00','2023-08-17 08:02:00'),(220,21,'POADR1',656,685,30,'Alamat ID 1','2023-08-17 08:02:00','2023-08-17 08:02:00'),(221,21,'POADR2',686,715,30,'Alamat ID 2','2023-08-17 08:02:00','2023-08-17 08:02:00'),(222,21,'POADR3',716,745,30,'Kelurahan','2023-08-17 08:02:00','2023-08-17 08:02:00'),(223,21,'POADR4',746,775,30,'Kecamatan','2023-08-17 08:02:00','2023-08-17 08:02:00'),(224,21,'POADR5',776,805,30,'Kota','2023-08-17 08:02:00','2023-08-17 08:02:00'),(225,21,'POADR6',806,835,30,'Propinsi','2023-08-17 08:02:00','2023-08-17 08:02:00'),(226,21,'POZIPC',836,842,7,'Kode Pos','2023-08-17 08:02:00','2023-08-17 08:02:00'),(227,21,'PODML1',843,872,30,'Domisili BO 1','2023-08-17 08:02:00','2023-08-17 08:02:00'),(228,21,'PODML2',873,902,30,'Domisili BO 2','2023-08-17 08:02:00','2023-08-17 08:02:00'),(229,21,'PODML3',903,932,30,'Domisili BO 3','2023-08-17 08:02:00','2023-08-17 08:02:00'),(230,21,'PODML4',933,962,30,'Domisili BO 4','2023-08-17 08:02:00','2023-08-17 08:02:00'),(231,21,'POPHA1',963,966,4,'Kode Area','2023-08-17 08:02:00','2023-08-17 08:02:00'),(232,21,'POPNA1',967,975,9,'Telephon','2023-08-17 08:02:00','2023-08-17 08:02:00'),(233,21,'POMBLN',976,990,15,'Handphone','2023-08-17 08:02:00','2023-08-17 08:02:00'),(234,21,'POJEKL',991,991,1,'Jenis Kelamin','2023-08-17 08:02:00','2023-08-17 08:02:00'),(235,21,'POAGAM',992,992,1,'Agama','2023-08-17 08:02:00','2023-08-17 08:02:00'),(236,21,'POPLBR',993,1022,30,'Tempat Lahir','2023-08-17 08:02:00','2023-08-17 08:02:00'),(237,21,'PODTLH',1023,1030,8,'Tgl Lahir','2023-08-17 08:02:00','2023-08-17 08:02:00'),(238,21,'POMRST',1031,1031,1,'Status Perkawinan','2023-08-17 08:02:00','2023-08-17 08:02:00'),(239,21,'POFRDN',1032,1035,4,'Sumber Dana BO','2023-08-17 08:02:00','2023-08-17 08:02:00'),(240,21,'POFRDS',1036,1065,30,'Deskripsi Sumber Dana BO','2023-08-17 08:02:00','2023-08-17 08:02:00'),(241,21,'POTOIC',1066,1069,4,'Tujuan Dana BO','2023-08-17 08:02:00','2023-08-17 08:02:00'),(242,21,'POTODS',1070,1099,30,'Deskripsi Tujuan Dana BO','2023-08-17 08:02:00','2023-08-17 08:02:00'),(243,21,'POINCM',1100,1101,2,'Rata-Rata Penghasilan','2023-08-17 08:02:00','2023-08-17 08:02:00'),(244,21,'POINFO',1102,1131,30,'Informasi BO','2023-08-17 08:02:00','2023-08-17 08:02:00'),(245,21,'POTBSN',1132,1161,30,'No. Izin Usaha','2023-08-17 08:02:00','2023-08-17 08:02:00'),(246,21,'POCPTY',1162,1162,1,'Bentuk Usaha','2023-08-17 08:02:00','2023-08-17 08:02:00'),(247,21,'POTCPC',1163,1166,4,'Bidang Usaha','2023-08-17 08:02:00','2023-08-17 08:02:00'),(248,21,'PUIDPR',1167,1167,1,'Identitas Nasabah','2023-08-17 08:02:00','2023-08-17 08:02:00'),(249,21,'PULUPR',1168,1168,1,'Lokasi Usaha','2023-08-17 08:02:00','2023-08-17 08:02:00'),(250,21,'PUPNPR',1169,1169,1,'Profil Nasabah','2023-08-17 08:02:00','2023-08-17 08:02:00'),(251,21,'PUJTPR',1170,1170,1,'Jumlah Transaksi','2023-08-17 08:02:00','2023-08-17 08:02:00'),(252,21,'PUKUPR',1171,1171,1,'Kegiatan Usaha','2023-08-17 08:02:00','2023-08-17 08:02:00'),(253,21,'PUSKPR',1172,1172,1,'Struktur Kepemilikan','2023-08-17 08:02:00','2023-08-17 08:02:00'),(254,21,'PUIFPR',1173,1173,1,'Informasi Lain','2023-08-17 08:02:00','2023-08-17 08:02:00'),(255,21,'PURSPR',1174,1174,1,'Resume Akhir','2023-08-17 08:02:00','2023-08-17 08:02:00'),(256,21,'PUBRCO',1175,1177,3,'Branch','2023-08-17 08:02:00','2023-08-17 08:02:00'),(257,21,'PUDTBG',1178,1185,8,'Beginning Date Customer','2023-08-17 08:02:00','2023-08-17 08:02:00'),(258,21,'PUGDIN',1186,1193,8,'Date Input','2023-08-17 08:02:00','2023-08-17 08:02:00'),(259,21,'PUGTIN',1194,1199,6,'Time Input','2023-08-17 08:02:00','2023-08-17 08:02:00'),(260,21,'PUGUIN',1200,1209,10,'User Input','2023-08-17 08:02:00','2023-08-17 08:02:00'),(261,21,'PUGUOT',1210,1219,10,'User Otor','2023-08-17 08:02:00','2023-08-17 08:02:00'),(262,21,'PUGTOT',1220,1225,6,'Time Otor','2023-08-17 08:02:00','2023-08-17 08:02:00'),(263,21,'PUGDLT',1226,1233,8,'Date Last Update','2023-08-17 08:02:00','2023-08-17 08:02:00'),(264,22,'CMSTAT',1,1,1,'Status record','2023-08-22 04:41:22','2023-08-22 04:41:22'),(265,22,'CMSTAD',2,2,1,'Status Data','2023-08-22 04:41:22','2023-08-22 04:41:22'),(266,22,'CMBRCA',3,4,2,'Wilayah','2023-08-22 04:41:22','2023-08-22 04:41:22'),(267,22,'CMBRCD',5,7,3,'Branch','2023-08-22 04:41:22','2023-08-22 04:41:22'),(268,22,'CMCSNO',8,15,8,'Cust.No  Fac.','2023-08-22 04:41:22','2023-08-22 04:41:22'),(269,22,'CMFCTY',16,18,3,'Facility Type','2023-08-22 04:41:22','2023-08-22 04:41:22'),(270,22,'CMFCSQ',19,20,2,'Sequence No','2023-08-22 04:41:22','2023-08-22 04:41:22'),(271,22,'CMSEQN',21,24,4,'Sequence No','2023-08-22 04:41:22','2023-08-22 04:41:22'),(272,22,'CMCLCD',25,27,3,'Collateral Code','2023-08-22 04:41:22','2023-08-22 04:41:22'),(273,22,'CMCOTY',28,29,2,'Coll.Group','2023-08-22 04:41:22','2023-08-22 04:41:22'),(274,22,'CMCYCD',30,32,3,'Loan Ccy Code','2023-08-22 04:41:22','2023-08-22 04:41:22'),(275,22,'CMNRDT',33,40,8,'Next Review Date','2023-08-22 04:41:22','2023-08-22 04:41:22'),(276,22,'CMLRDT',41,48,8,'Last Review Date','2023-08-22 04:41:22','2023-08-22 04:41:22'),(277,22,'CMMTAS',49,56,8,'Maturity Assuransi','2023-08-22 04:41:22','2023-08-22 04:41:22'),(278,22,'CMREFR',57,57,1,'Review Freq','2023-08-22 04:41:22','2023-08-22 04:41:22'),(279,22,'CMDAYN',58,59,2,'Review Day No','2023-08-22 04:41:22','2023-08-22 04:41:22'),(280,22,'CMSTDT',60,67,8,'Start Date','2023-08-22 04:41:22','2023-08-22 04:41:22'),(281,22,'CMMTDT',68,75,8,'Maturity Date','2023-08-22 04:41:22','2023-08-22 04:41:22'),(282,22,'CMFQTY',76,78,3,'Quantity','2023-08-22 04:41:22','2023-08-22 04:41:22'),(283,22,'CMAMNT',79,93,15,'Amount (Orig. CCY)','2023-08-22 04:41:22','2023-08-22 04:41:22'),(284,22,'CMAMIK',94,108,15,'Legal.Amt in Orgi-Ccy','2023-08-22 04:41:22','2023-08-22 04:41:22'),(285,22,'CMAMTC',109,123,15,'Amount (Base CCY)','2023-08-22 04:41:22','2023-08-22 04:41:22'),(286,22,'CMAMIB',124,138,15,'Legal.Amt in Base-Ccy','2023-08-22 04:41:22','2023-08-22 04:41:22'),(287,22,'CMGIVB',139,168,30,'Given By','2023-08-22 04:41:22','2023-08-22 04:41:22'),(288,22,'CMNARR',169,258,90,'Narrative','2023-08-22 04:41:22','2023-08-22 04:41:22'),(289,22,'CMLCSQ',259,260,2,'Last Coll Seq.No','2023-08-22 04:41:22','2023-08-22 04:41:22'),(290,22,'CMTCSQ',261,262,2,'Total Coll Seq.No','2023-08-22 04:41:22','2023-08-22 04:41:22'),(291,22,'CMFCOC',263,264,2,'From Area Code','2023-08-22 04:41:22','2023-08-22 04:41:22'),(292,22,'CMFBRC',265,267,3,'From Branch Code','2023-08-22 04:41:22','2023-08-22 04:41:22'),(293,22,'CMPGIK',268,269,2,'Kode Pengikatan','2023-08-22 04:41:22','2023-08-22 04:41:22'),(294,22,'CMASRC',270,299,30,'Asuransi','2023-08-22 04:41:22','2023-08-22 04:41:22'),(295,22,'CMDEPN',300,306,7,'Deposito Number','2023-08-22 04:41:22','2023-08-22 04:41:22'),(296,22,'CMBLDF',307,307,1,'Bloked Deposito','2023-08-22 04:41:22','2023-08-22 04:41:22'),(297,22,'CMFJDE',308,315,8,'1st Job  Date Entry','2023-08-22 04:41:22','2023-08-22 04:41:22'),(298,22,'CMUSID',316,325,10,'User ID','2023-08-22 04:41:22','2023-08-22 04:41:22'),(299,22,'CMWSID',326,335,10,'Display ID','2023-08-22 04:41:22','2023-08-22 04:41:22'),(300,22,'CMLSDA',336,343,8,'Last Sys Date Amend','2023-08-22 04:41:22','2023-08-22 04:41:22'),(301,22,'CMLJDA',344,351,8,'Last Job Date Amend','2023-08-22 04:41:22','2023-08-22 04:41:22'),(302,22,'CMLSTA',352,357,6,'Last Time Amend','2023-08-22 04:41:22','2023-08-22 04:41:22'),(303,22,'CMAUUS',358,367,10,'Authorize By','2023-08-22 04:41:22','2023-08-22 04:41:22'),(304,22,'CMLOCA',368,387,20,'Coll.Location','2023-08-22 04:41:22','2023-08-22 04:41:22'),(305,23,'BSSTAT',1,1,1,'Status record','2023-08-25 07:12:33','2023-08-25 07:12:33'),(306,23,'BSCODE',2,3,2,'Base Rate','2023-08-25 07:12:33','2023-08-25 07:12:33'),(307,23,'BSNAME',4,33,30,'Base Rate Name','2023-08-25 07:12:33','2023-08-25 07:12:33'),(308,23,'BSRATE',34,37,4,'Rate % 999.9999','2023-08-25 07:12:33','2023-08-25 07:12:33'),(309,23,'BSDTVL',38,45,8,'Tanggal Valuta','2023-08-25 07:12:33','2023-08-25 07:12:33'),(310,23,'BSDTLC',46,53,8,'Tanggal diubah','2023-08-25 07:12:33','2023-08-25 07:12:33');
/*!40000 ALTER TABLE `mst_file_content_dictionary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mst_file_dictionary`
--

DROP TABLE IF EXISTS `mst_file_dictionary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_file_dictionary` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` tinytext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_file_dictionary`
--

LOCK TABLES `mst_file_dictionary` WRITE;
/*!40000 ALTER TABLE `mst_file_dictionary` DISABLE KEYS */;
INSERT INTO `mst_file_dictionary` VALUES (14,'A0CY','Tabel Mata Uang','2023-08-14 11:35:34','2023-08-14 11:35:34'),(18,'A0WI','Tabel Wilayah','2023-08-15 07:40:08','2023-08-15 07:40:08'),(19,'LLOAN','Data Master Loan','2023-08-15 07:52:00','2023-08-15 07:52:00'),(20,'LBILND','Master Data Lapbul Loan','2023-08-17 07:42:27','2023-08-17 07:42:27'),(21,'M4CUAPU','Master CIF General APU','2023-08-17 08:02:00','2023-08-17 08:02:00'),(22,'LCOMN','Data Master Jaminan Pinjaman','2023-08-22 04:41:22','2023-08-22 04:41:22'),(23,'A1BS','Tabel Suku Bunga Dasar','2023-08-25 07:12:33','2023-08-25 07:12:33');
/*!40000 ALTER TABLE `mst_file_dictionary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notification_templates`
--

DROP TABLE IF EXISTS `notification_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notification_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_id` bigint unsigned NOT NULL,
  `role_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `all_role` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notification_templates_action_id_foreign` (`action_id`),
  KEY `notification_templates_role_id_foreign` (`role_id`),
  CONSTRAINT `notification_templates_action_id_foreign` FOREIGN KEY (`action_id`) REFERENCES `actions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notification_templates`
--

LOCK TABLES `notification_templates` WRITE;
/*!40000 ALTER TABLE `notification_templates` DISABLE KEYS */;
INSERT INTO `notification_templates` VALUES (1,'Data pengajuan baru','Terdapat data pengajuan baru.',2,NULL,1,NULL,NULL),(2,'Tanggal Ketersediaan Unit','Tanggal ketersediaan unit telah diatur.',6,'1,2',0,NULL,NULL),(3,'Bukti pembayaran','Upload bukti pembayaran telah dilakukan.',4,'1,3',0,NULL,NULL),(4,'Konfirmasi Bukti Pembayaran','Bukti bukti pembayaran telah dikonfirmasi.',5,'1,2',0,NULL,NULL),(5,'Tanggal Penyerahan Unit','Tanggal penyerahan unit telah diatur.',7,'1,2',0,NULL,NULL),(6,'Kofirmasi Bukti Penyerahan Unit','Bukti penyerahan unit telah dikonfirmasi.',8,'1,3',0,NULL,NULL),(7,'Upload STNK','STNK telah diupload.',9,'1,2',0,NULL,NULL),(8,'Upload Polis','Polis telah diupload.',10,'1,2',0,NULL,NULL),(9,'Upload BPKB','BPKB telah diupload.',11,'1,2',0,NULL,NULL),(10,'Konfirmasi STNK','STNK telah dikonfirmasi.',12,'1,3',0,NULL,NULL),(11,'Konfirmasi Polis','Polis telah dikonfirmasi.',13,'1,3',0,NULL,NULL),(12,'Konfirmasi BPKB','BPKB telah dikonfirmasi.',14,'1,3',0,NULL,NULL);
/*!40000 ALTER TABLE `notification_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kredit_id` bigint unsigned NOT NULL,
  `template_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `extra` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `read` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_template_id_foreign` (`template_id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  KEY `notifications_kredit_id_foreign` (`kredit_id`),
  CONSTRAINT `notifications_kredit_id_foreign` FOREIGN KEY (`kredit_id`) REFERENCES `kredits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notifications_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `notification_templates` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=249 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (5,2,1,1,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 001/PO/06/2023</a></li>',0,'2023-06-05 20:34:33','2023-06-05 20:34:33'),(6,2,1,2,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 001/PO/06/2023</a></li>',1,'2023-06-05 20:34:33','2023-06-05 22:15:06'),(7,2,1,3,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 001/PO/06/2023</a></li>',0,'2023-06-05 20:34:33','2023-06-05 20:34:33'),(8,2,1,4,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 001/PO/06/2023</a></li>',1,'2023-06-05 20:34:33','2023-08-10 06:57:09'),(17,2,1,1,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 001/SBY/PO/07/2023</a></li>',0,'2023-07-31 06:23:08','2023-07-31 06:23:08'),(18,2,1,2,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 001/SBY/PO/07/2023</a></li>',0,'2023-07-31 06:23:08','2023-07-31 06:23:08'),(19,2,1,3,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 001/SBY/PO/07/2023</a></li>',0,'2023-07-31 06:23:08','2023-07-31 06:23:08'),(20,2,1,4,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 001/SBY/PO/07/2023</a></li>',1,'2023-07-31 06:23:08','2023-08-10 06:57:05'),(21,3,1,1,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 002/PMK/PO/07/2023</a></li>',0,'2023-07-31 06:38:39','2023-07-31 06:38:39'),(22,3,1,2,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 002/PMK/PO/07/2023</a></li>',0,'2023-07-31 06:38:39','2023-07-31 06:38:39'),(23,3,1,3,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 002/PMK/PO/07/2023</a></li>',1,'2023-07-31 06:38:40','2023-08-22 08:30:47'),(24,3,1,4,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 002/PMK/PO/07/2023</a></li>',1,'2023-07-31 06:38:40','2023-08-10 06:56:58'),(25,4,1,1,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 001/SBY/PO/08/2023</a></li>',0,'2023-08-10 07:43:00','2023-08-10 07:43:00'),(26,4,1,2,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 001/SBY/PO/08/2023</a></li>',0,'2023-08-10 07:43:00','2023-08-10 07:43:00'),(27,4,1,3,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 001/SBY/PO/08/2023</a></li>',1,'2023-08-10 07:43:00','2023-08-22 08:30:44'),(28,4,1,4,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 001/SBY/PO/08/2023</a></li>',0,'2023-08-10 07:43:00','2023-08-10 07:43:00'),(29,4,1,5,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 001/SBY/PO/08/2023</a></li>',0,'2023-08-10 07:43:00','2023-08-10 07:43:00'),(30,5,1,1,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 002/SBY/PO/08/2023</a></li>',0,'2023-08-10 07:45:13','2023-08-10 07:45:13'),(31,5,1,2,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 002/SBY/PO/08/2023</a></li>',0,'2023-08-10 07:45:13','2023-08-10 07:45:13'),(32,5,1,3,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 002/SBY/PO/08/2023</a></li>',0,'2023-08-10 07:45:13','2023-08-10 07:45:13'),(33,5,1,4,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 002/SBY/PO/08/2023</a></li>',0,'2023-08-10 07:45:13','2023-08-10 07:45:13'),(34,5,1,5,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 002/SBY/PO/08/2023</a></li>',0,'2023-08-10 07:45:13','2023-08-10 07:45:13'),(35,6,1,1,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 003/SBY/PO/08/2023</a></li>',0,'2023-08-10 07:46:02','2023-08-10 07:46:02'),(36,6,1,2,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 003/SBY/PO/08/2023</a></li>',0,'2023-08-10 07:46:02','2023-08-10 07:46:02'),(37,6,1,3,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 003/SBY/PO/08/2023</a></li>',1,'2023-08-10 07:46:02','2023-08-22 08:30:42'),(38,6,1,4,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 003/SBY/PO/08/2023</a></li>',0,'2023-08-10 07:46:02','2023-08-10 07:46:02'),(39,6,1,5,'<li><a href=\"https://develop.bankumkm.id/datawarehouse/kredit\">Nomor Pengajuan : 003/SBY/PO/08/2023</a></li>',0,'2023-08-10 07:46:02','2023-08-10 07:46:02'),(40,1,2,1,NULL,0,'2023-08-22 08:09:51','2023-08-22 08:09:51'),(41,1,2,2,NULL,1,'2023-08-22 08:09:51','2023-08-22 08:10:39'),(42,1,3,1,NULL,0,'2023-08-22 08:10:57','2023-08-22 08:10:57'),(43,1,3,4,NULL,0,'2023-08-22 08:10:57','2023-08-22 08:10:57'),(44,1,3,5,NULL,0,'2023-08-22 08:10:57','2023-08-22 08:10:57'),(45,1,4,1,NULL,0,'2023-08-22 08:11:33','2023-08-22 08:11:33'),(46,1,4,2,NULL,0,'2023-08-22 08:11:33','2023-08-22 08:11:33'),(47,1,5,1,NULL,0,'2023-08-22 08:12:29','2023-08-22 08:12:29'),(48,1,5,2,NULL,0,'2023-08-22 08:12:29','2023-08-22 08:12:29'),(49,1,6,1,NULL,0,'2023-08-22 08:13:02','2023-08-22 08:13:02'),(50,1,6,4,NULL,0,'2023-08-22 08:13:02','2023-08-22 08:13:02'),(51,1,6,5,NULL,0,'2023-08-22 08:13:02','2023-08-22 08:13:02'),(52,1,7,1,NULL,0,'2023-08-22 08:14:13','2023-08-22 08:14:13'),(53,1,7,2,NULL,0,'2023-08-22 08:14:13','2023-08-22 08:14:13'),(54,1,10,1,NULL,0,'2023-08-22 08:14:47','2023-08-22 08:14:47'),(55,1,10,4,NULL,0,'2023-08-22 08:14:47','2023-08-22 08:14:47'),(56,1,10,5,NULL,0,'2023-08-22 08:14:47','2023-08-22 08:14:47'),(57,1,8,1,NULL,0,'2023-08-22 08:15:32','2023-08-22 08:15:32'),(58,1,8,2,NULL,0,'2023-08-22 08:15:32','2023-08-22 08:15:32'),(59,1,9,1,NULL,0,'2023-08-22 08:15:32','2023-08-22 08:15:32'),(60,1,9,2,NULL,0,'2023-08-22 08:15:32','2023-08-22 08:15:32'),(61,1,11,1,NULL,0,'2023-08-22 08:16:02','2023-08-22 08:16:02'),(62,1,11,4,NULL,0,'2023-08-22 08:16:02','2023-08-22 08:16:02'),(63,1,11,5,NULL,0,'2023-08-22 08:16:02','2023-08-22 08:16:02'),(64,1,12,1,NULL,0,'2023-08-22 08:16:02','2023-08-22 08:16:02'),(65,1,12,4,NULL,1,'2023-08-22 08:16:02','2023-08-22 08:20:51'),(66,1,12,5,NULL,0,'2023-08-22 08:16:02','2023-08-22 08:16:02'),(67,1,7,1,NULL,0,'2023-08-22 08:16:53','2023-08-22 08:16:53'),(68,1,7,2,NULL,0,'2023-08-22 08:16:53','2023-08-22 08:16:53'),(69,1,7,1,NULL,0,'2023-08-22 08:17:39','2023-08-22 08:17:39'),(70,1,7,2,NULL,0,'2023-08-22 08:17:39','2023-08-22 08:17:39'),(71,2,2,1,NULL,0,'2023-09-03 08:01:35','2023-09-03 08:01:35'),(72,2,2,2,NULL,0,'2023-09-03 08:01:35','2023-09-03 08:01:35'),(73,2,3,1,NULL,0,'2023-09-03 08:40:41','2023-09-03 08:40:41'),(74,2,3,4,NULL,0,'2023-09-03 08:40:41','2023-09-03 08:40:41'),(75,2,3,5,NULL,0,'2023-09-03 08:40:41','2023-09-03 08:40:41'),(76,2,3,6,NULL,0,'2023-09-03 08:40:41','2023-09-03 08:40:41'),(77,2,3,1,NULL,0,'2023-09-03 08:45:24','2023-09-03 08:45:24'),(78,2,3,4,NULL,0,'2023-09-03 08:45:24','2023-09-03 08:45:24'),(79,2,3,5,NULL,0,'2023-09-03 08:45:24','2023-09-03 08:45:24'),(80,2,3,6,NULL,0,'2023-09-03 08:45:24','2023-09-03 08:45:24'),(81,2,4,1,NULL,0,'2023-09-03 09:14:37','2023-09-03 09:14:37'),(82,2,4,2,NULL,0,'2023-09-03 09:14:37','2023-09-03 09:14:37'),(83,2,5,1,NULL,0,'2023-09-03 09:30:37','2023-09-03 09:30:37'),(84,2,5,2,NULL,0,'2023-09-03 09:30:37','2023-09-03 09:30:37'),(85,2,6,1,NULL,0,'2023-09-03 09:54:08','2023-09-03 09:54:08'),(86,2,6,4,NULL,0,'2023-09-03 09:54:08','2023-09-03 09:54:08'),(87,2,6,5,NULL,0,'2023-09-03 09:54:08','2023-09-03 09:54:08'),(88,2,6,6,NULL,0,'2023-09-03 09:54:08','2023-09-03 09:54:08'),(89,2,7,1,NULL,0,'2023-09-03 10:21:36','2023-09-03 10:21:36'),(90,2,7,2,NULL,0,'2023-09-03 10:21:36','2023-09-03 10:21:36'),(91,2,10,1,NULL,0,'2023-09-03 10:24:09','2023-09-03 10:24:09'),(92,2,10,4,NULL,0,'2023-09-03 10:24:09','2023-09-03 10:24:09'),(93,2,10,5,NULL,0,'2023-09-03 10:24:09','2023-09-03 10:24:09'),(94,2,10,6,NULL,0,'2023-09-03 10:24:09','2023-09-03 10:24:09'),(95,2,8,1,NULL,0,'2023-09-03 10:24:28','2023-09-03 10:24:28'),(96,2,8,2,NULL,0,'2023-09-03 10:24:28','2023-09-03 10:24:28'),(97,2,9,1,NULL,0,'2023-09-03 10:24:28','2023-09-03 10:24:28'),(98,2,9,2,NULL,0,'2023-09-03 10:24:28','2023-09-03 10:24:28'),(99,2,11,1,NULL,0,'2023-09-03 10:24:39','2023-09-03 10:24:39'),(100,2,11,4,NULL,0,'2023-09-03 10:24:39','2023-09-03 10:24:39'),(101,2,11,5,NULL,0,'2023-09-03 10:24:39','2023-09-03 10:24:39'),(102,2,11,6,NULL,0,'2023-09-03 10:24:39','2023-09-03 10:24:39'),(103,2,12,1,NULL,0,'2023-09-03 10:24:39','2023-09-03 10:24:39'),(104,2,12,4,NULL,0,'2023-09-03 10:24:39','2023-09-03 10:24:39'),(105,2,12,5,NULL,0,'2023-09-03 10:24:39','2023-09-03 10:24:39'),(106,2,12,6,NULL,0,'2023-09-03 10:24:39','2023-09-03 10:24:39'),(107,2,7,1,NULL,0,'2023-09-03 10:35:39','2023-09-03 10:35:39'),(108,2,7,2,NULL,0,'2023-09-03 10:35:39','2023-09-03 10:35:39'),(109,2,7,1,NULL,0,'2023-09-03 10:46:28','2023-09-03 10:46:28'),(110,2,7,2,NULL,0,'2023-09-03 10:46:28','2023-09-03 10:46:28'),(111,3,2,1,NULL,0,'2023-09-03 15:49:35','2023-09-03 15:49:35'),(112,3,2,2,NULL,0,'2023-09-03 15:49:35','2023-09-03 15:49:35'),(113,3,3,1,NULL,0,'2023-09-03 15:53:46','2023-09-03 15:53:46'),(114,3,3,4,NULL,0,'2023-09-03 15:53:46','2023-09-03 15:53:46'),(115,3,3,5,NULL,0,'2023-09-03 15:53:46','2023-09-03 15:53:46'),(116,3,3,6,NULL,0,'2023-09-03 15:53:46','2023-09-03 15:53:46'),(117,3,4,1,NULL,0,'2023-09-03 15:57:12','2023-09-03 15:57:12'),(118,3,4,2,NULL,0,'2023-09-03 15:57:12','2023-09-03 15:57:12'),(119,3,5,1,NULL,0,'2023-09-03 15:59:24','2023-09-03 15:59:24'),(120,3,5,2,NULL,0,'2023-09-03 15:59:24','2023-09-03 15:59:24'),(121,3,6,1,NULL,0,'2023-09-03 16:01:54','2023-09-03 16:01:54'),(122,3,6,4,NULL,0,'2023-09-03 16:01:54','2023-09-03 16:01:54'),(123,3,6,5,NULL,0,'2023-09-03 16:01:54','2023-09-03 16:01:54'),(124,3,6,6,NULL,0,'2023-09-03 16:01:54','2023-09-03 16:01:54'),(125,3,7,1,NULL,0,'2023-09-03 16:04:26','2023-09-03 16:04:26'),(126,3,7,2,NULL,0,'2023-09-03 16:04:26','2023-09-03 16:04:26'),(127,3,8,1,NULL,0,'2023-09-03 16:04:26','2023-09-03 16:04:26'),(128,3,8,2,NULL,0,'2023-09-03 16:04:26','2023-09-03 16:04:26'),(129,3,9,1,NULL,0,'2023-09-03 16:04:26','2023-09-03 16:04:26'),(130,3,9,2,NULL,0,'2023-09-03 16:04:26','2023-09-03 16:04:26'),(131,3,10,1,NULL,0,'2023-09-03 16:04:53','2023-09-03 16:04:53'),(132,3,10,4,NULL,0,'2023-09-03 16:04:53','2023-09-03 16:04:53'),(133,3,10,5,NULL,0,'2023-09-03 16:04:53','2023-09-03 16:04:53'),(134,3,10,6,NULL,0,'2023-09-03 16:04:53','2023-09-03 16:04:53'),(135,3,11,1,NULL,0,'2023-09-03 16:04:53','2023-09-03 16:04:53'),(136,3,11,4,NULL,0,'2023-09-03 16:04:53','2023-09-03 16:04:53'),(137,3,11,5,NULL,0,'2023-09-03 16:04:53','2023-09-03 16:04:53'),(138,3,11,6,NULL,0,'2023-09-03 16:04:53','2023-09-03 16:04:53'),(139,3,12,1,NULL,0,'2023-09-03 16:04:53','2023-09-03 16:04:53'),(140,3,12,4,NULL,0,'2023-09-03 16:04:53','2023-09-03 16:04:53'),(141,3,12,5,NULL,0,'2023-09-03 16:04:53','2023-09-03 16:04:53'),(142,3,12,6,NULL,0,'2023-09-03 16:04:53','2023-09-03 16:04:53'),(143,3,7,1,NULL,0,'2023-09-03 16:05:30','2023-09-03 16:05:30'),(144,3,7,2,NULL,0,'2023-09-03 16:05:30','2023-09-03 16:05:30'),(145,3,7,1,NULL,0,'2023-09-03 16:07:18','2023-09-03 16:07:18'),(146,3,7,2,NULL,0,'2023-09-03 16:07:18','2023-09-03 16:07:18'),(147,6,2,1,NULL,0,'2023-09-03 18:28:00','2023-09-03 18:28:00'),(148,6,2,2,NULL,0,'2023-09-03 18:28:00','2023-09-03 18:28:00'),(149,6,3,1,NULL,0,'2023-09-03 18:28:47','2023-09-03 18:28:47'),(150,6,3,4,NULL,0,'2023-09-03 18:28:47','2023-09-03 18:28:47'),(151,6,3,5,NULL,0,'2023-09-03 18:28:47','2023-09-03 18:28:47'),(152,6,3,6,NULL,0,'2023-09-03 18:28:47','2023-09-03 18:28:47'),(153,6,4,1,NULL,0,'2023-09-03 18:28:59','2023-09-03 18:28:59'),(154,6,4,2,NULL,0,'2023-09-03 18:28:59','2023-09-03 18:28:59'),(155,6,5,1,NULL,0,'2023-09-03 18:40:33','2023-09-03 18:40:33'),(156,6,5,2,NULL,0,'2023-09-03 18:40:33','2023-09-03 18:40:33'),(157,6,6,1,NULL,0,'2023-09-03 18:40:42','2023-09-03 18:40:42'),(158,6,6,4,NULL,0,'2023-09-03 18:40:42','2023-09-03 18:40:42'),(159,6,6,5,NULL,0,'2023-09-03 18:40:42','2023-09-03 18:40:42'),(160,6,6,6,NULL,0,'2023-09-03 18:40:42','2023-09-03 18:40:42'),(161,6,7,1,NULL,0,'2023-09-03 18:47:52','2023-09-03 18:47:52'),(162,6,7,2,NULL,0,'2023-09-03 18:47:52','2023-09-03 18:47:52'),(163,6,8,1,NULL,0,'2023-09-03 18:47:52','2023-09-03 18:47:52'),(164,6,8,2,NULL,0,'2023-09-03 18:47:52','2023-09-03 18:47:52'),(165,6,9,1,NULL,0,'2023-09-03 18:47:52','2023-09-03 18:47:52'),(166,6,9,2,NULL,1,'2023-09-03 18:47:52','2023-09-03 21:53:39'),(167,4,2,1,NULL,0,'2023-09-04 04:11:37','2023-09-04 04:11:37'),(168,4,2,2,NULL,0,'2023-09-04 04:11:37','2023-09-04 04:11:37'),(169,6,2,1,NULL,0,'2023-09-04 04:12:01','2023-09-04 04:12:01'),(170,6,2,2,NULL,0,'2023-09-04 04:12:01','2023-09-04 04:12:01'),(171,6,2,1,NULL,0,'2023-09-04 04:14:00','2023-09-04 04:14:00'),(172,6,2,2,NULL,0,'2023-09-04 04:14:00','2023-09-04 04:14:00'),(173,6,3,1,NULL,0,'2023-09-04 04:18:49','2023-09-04 04:18:49'),(174,6,3,4,NULL,0,'2023-09-04 04:18:49','2023-09-04 04:18:49'),(175,6,3,5,NULL,0,'2023-09-04 04:18:49','2023-09-04 04:18:49'),(176,6,3,6,NULL,0,'2023-09-04 04:18:49','2023-09-04 04:18:49'),(177,6,3,1,NULL,0,'2023-09-04 04:21:30','2023-09-04 04:21:30'),(178,6,3,4,NULL,0,'2023-09-04 04:21:30','2023-09-04 04:21:30'),(179,6,3,5,NULL,0,'2023-09-04 04:21:30','2023-09-04 04:21:30'),(180,6,3,6,NULL,0,'2023-09-04 04:21:30','2023-09-04 04:21:30'),(181,6,4,1,NULL,0,'2023-09-04 04:22:08','2023-09-04 04:22:08'),(182,6,4,2,NULL,0,'2023-09-04 04:22:08','2023-09-04 04:22:08'),(183,6,5,1,NULL,0,'2023-09-04 04:23:06','2023-09-04 04:23:06'),(184,6,5,2,NULL,0,'2023-09-04 04:23:06','2023-09-04 04:23:06'),(185,6,6,1,NULL,0,'2023-09-04 04:25:37','2023-09-04 04:25:37'),(186,6,6,4,NULL,0,'2023-09-04 04:25:37','2023-09-04 04:25:37'),(187,6,6,5,NULL,0,'2023-09-04 04:25:37','2023-09-04 04:25:37'),(188,6,6,6,NULL,0,'2023-09-04 04:25:37','2023-09-04 04:25:37'),(189,6,7,1,NULL,0,'2023-09-04 04:34:48','2023-09-04 04:34:48'),(190,6,7,2,NULL,0,'2023-09-04 04:34:48','2023-09-04 04:34:48'),(191,6,9,1,NULL,0,'2023-09-04 04:34:49','2023-09-04 04:34:49'),(192,6,9,2,NULL,0,'2023-09-04 04:34:49','2023-09-04 04:34:49'),(193,6,10,1,NULL,0,'2023-09-04 05:15:01','2023-09-04 05:15:01'),(194,6,10,4,NULL,0,'2023-09-04 05:15:01','2023-09-04 05:15:01'),(195,6,10,5,NULL,0,'2023-09-04 05:15:01','2023-09-04 05:15:01'),(196,6,10,6,NULL,0,'2023-09-04 05:15:01','2023-09-04 05:15:01'),(201,6,10,1,NULL,0,'2023-09-04 05:17:29','2023-09-04 05:17:29'),(202,6,10,4,NULL,0,'2023-09-04 05:17:29','2023-09-04 05:17:29'),(203,6,10,5,NULL,0,'2023-09-04 05:17:29','2023-09-04 05:17:29'),(204,6,10,6,NULL,0,'2023-09-04 05:17:29','2023-09-04 05:17:29'),(205,6,10,1,NULL,0,'2023-09-04 05:30:20','2023-09-04 05:30:20'),(206,6,10,4,NULL,0,'2023-09-04 05:30:20','2023-09-04 05:30:20'),(207,6,10,5,NULL,0,'2023-09-04 05:30:20','2023-09-04 05:30:20'),(208,6,10,6,NULL,0,'2023-09-04 05:30:20','2023-09-04 05:30:20'),(209,6,7,1,NULL,0,'2023-09-04 05:32:07','2023-09-04 05:32:07'),(210,6,7,2,NULL,0,'2023-09-04 05:32:07','2023-09-04 05:32:07'),(211,6,8,1,NULL,0,'2023-09-04 05:32:07','2023-09-04 05:32:07'),(212,6,8,2,NULL,0,'2023-09-04 05:32:07','2023-09-04 05:32:07'),(213,6,9,1,NULL,0,'2023-09-04 05:32:07','2023-09-04 05:32:07'),(214,6,9,2,NULL,0,'2023-09-04 05:32:07','2023-09-04 05:32:07'),(215,6,10,1,NULL,0,'2023-09-04 05:32:22','2023-09-04 05:32:22'),(216,6,10,4,NULL,0,'2023-09-04 05:32:22','2023-09-04 05:32:22'),(217,6,10,5,NULL,0,'2023-09-04 05:32:22','2023-09-04 05:32:22'),(218,6,10,6,NULL,0,'2023-09-04 05:32:22','2023-09-04 05:32:22'),(219,6,7,1,NULL,0,'2023-09-04 05:32:30','2023-09-04 05:32:30'),(220,6,7,2,NULL,0,'2023-09-04 05:32:30','2023-09-04 05:32:30'),(221,6,7,1,NULL,0,'2023-09-04 05:32:43','2023-09-04 05:32:43'),(222,6,7,2,NULL,0,'2023-09-04 05:32:43','2023-09-04 05:32:43'),(223,6,7,1,NULL,0,'2023-09-04 09:18:09','2023-09-04 09:18:09'),(224,6,7,2,NULL,0,'2023-09-04 09:18:09','2023-09-04 09:18:09'),(225,6,9,1,NULL,0,'2023-09-04 09:18:09','2023-09-04 09:18:09'),(226,6,9,2,NULL,0,'2023-09-04 09:18:09','2023-09-04 09:18:09'),(227,6,10,1,NULL,0,'2023-09-04 09:31:31','2023-09-04 09:31:31'),(228,6,10,4,NULL,0,'2023-09-04 09:31:31','2023-09-04 09:31:31'),(229,6,10,5,NULL,0,'2023-09-04 09:31:31','2023-09-04 09:31:31'),(230,6,10,6,NULL,0,'2023-09-04 09:31:31','2023-09-04 09:31:31'),(231,6,10,1,NULL,0,'2023-09-04 09:34:10','2023-09-04 09:34:10'),(232,6,10,4,NULL,0,'2023-09-04 09:34:10','2023-09-04 09:34:10'),(233,6,10,5,NULL,0,'2023-09-04 09:34:10','2023-09-04 09:34:10'),(234,6,10,6,NULL,0,'2023-09-04 09:34:10','2023-09-04 09:34:10'),(235,6,12,1,NULL,0,'2023-09-04 09:38:47','2023-09-04 09:38:47'),(236,6,12,4,NULL,0,'2023-09-04 09:38:47','2023-09-04 09:38:47'),(237,6,12,5,NULL,0,'2023-09-04 09:38:47','2023-09-04 09:38:47'),(238,6,12,6,NULL,0,'2023-09-04 09:38:47','2023-09-04 09:38:47'),(239,6,7,1,NULL,0,'2023-09-04 09:42:28','2023-09-04 09:42:28'),(240,6,7,2,NULL,0,'2023-09-04 09:42:28','2023-09-04 09:42:28'),(241,6,8,1,NULL,0,'2023-09-04 09:42:28','2023-09-04 09:42:28'),(242,6,8,2,NULL,0,'2023-09-04 09:42:28','2023-09-04 09:42:28'),(243,6,9,1,NULL,0,'2023-09-04 09:42:28','2023-09-04 09:42:28'),(244,6,9,2,NULL,0,'2023-09-04 09:42:28','2023-09-04 09:42:28'),(245,6,7,1,NULL,0,'2023-09-04 09:51:20','2023-09-04 09:51:20'),(246,6,7,2,NULL,0,'2023-09-04 09:51:20','2023-09-04 09:51:20'),(247,6,7,1,NULL,0,'2023-09-04 09:51:55','2023-09-04 09:51:55'),(248,6,7,2,NULL,0,'2023-09-04 09:51:55','2023-09-04 09:51:55');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `action_id` bigint unsigned DEFAULT NULL,
  `role_id` smallint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permissions_action_id_foreign` (`action_id`),
  KEY `permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `permissions_action_id_foreign` FOREIGN KEY (`action_id`) REFERENCES `actions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
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
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` smallint unsigned DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,NULL,'Pemasaran','2023-06-05 19:24:48','2023-06-05 19:24:48'),(2,NULL,'Cabang','2023-06-05 19:24:48','2023-06-05 19:24:48'),(3,NULL,'Vendor','2023-06-05 19:24:48','2023-06-05 19:24:48'),(4,NULL,'Superadmin','2023-06-05 19:24:48','2023-06-05 19:24:48'),(6,NULL,'Role testing','2023-09-02 19:06:16','2023-09-02 19:29:05'),(9,NULL,'asd','2023-09-02 20:21:18','2023-09-02 20:21:18');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `target`
--

DROP TABLE IF EXISTS `target`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `target` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nominal` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_unit` smallint unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `target`
--

LOCK TABLES `target` WRITE;
/*!40000 ALTER TABLE `target` DISABLE KEYS */;
INSERT INTO `target` VALUES (1,NULL,30,1,'2023-08-02 09:53:28','2023-08-22 08:29:37');
/*!40000 ALTER TABLE `target` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tenor_imbal_jasas`
--

DROP TABLE IF EXISTS `tenor_imbal_jasas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tenor_imbal_jasas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `imbaljasa_id` bigint unsigned NOT NULL,
  `tenor` int NOT NULL,
  `imbaljasa` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenor_imbal_jasas_imbaljasa_id_foreign` (`imbaljasa_id`),
  CONSTRAINT `tenor_imbal_jasas_imbaljasa_id_foreign` FOREIGN KEY (`imbaljasa_id`) REFERENCES `imbal_jasas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenor_imbal_jasas`
--

LOCK TABLES `tenor_imbal_jasas` WRITE;
/*!40000 ALTER TABLE `tenor_imbal_jasas` DISABLE KEYS */;
INSERT INTO `tenor_imbal_jasas` VALUES (1,1,12,100000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(2,1,24,200000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(3,1,36,300000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(4,2,12,150000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(5,2,24,300000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(6,2,36,400000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(7,3,12,200000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(8,3,24,400000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(9,3,36,550000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(10,4,12,250000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(11,4,24,450000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(12,4,36,700000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(13,5,12,300000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(14,5,24,550000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(15,5,36,800000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(16,6,12,350000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(17,6,24,650000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(18,6,36,950000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(19,7,12,400000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(20,7,24,750000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(21,7,36,1050000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(22,8,12,400000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(23,8,24,800000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(24,8,36,1300000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(25,9,12,450000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(26,9,24,900000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(27,9,36,1350000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(28,10,12,500000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(29,10,24,1000000,'2023-06-05 19:24:49','2023-06-05 19:24:49'),(30,10,36,1500000,'2023-06-05 19:24:49','2023-06-05 19:24:49');
/*!40000 ALTER TABLE `tenor_imbal_jasas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nip` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_cabang` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` bigint unsigned DEFAULT NULL,
  `role_id` smallint unsigned NOT NULL,
  `first_login` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_nip_unique` (`nip`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_vendor_id_foreign` (`vendor_id`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'123456789012345678',NULL,NULL,'$2y$10$d67OrX1GW7PB/1Yfd1mz4eOATzMRkrdTY9zSuahDJOG1XER8Nc7v6',NULL,1,0,NULL,'2023-06-05 19:24:48','2023-08-23 06:40:12'),(2,'01497',NULL,'001','$2y$10$WYqrKA93R6gCezra0XNJuuyV.Vak50wl9Nde3RcCicz887cjue7We',NULL,2,0,NULL,'2023-06-05 19:24:48','2023-08-23 06:39:49'),(3,'01474',NULL,NULL,'$2a$12$sjb9UjfMu4rU1g7bkZoxiuhePxKbpdRx5qLpCzFiLw.4AQC/uuFVK',NULL,4,1,NULL,'2023-06-05 19:24:48','2023-06-05 19:24:48'),(4,NULL,'bjsc@mail.com',NULL,'$2y$10$9rwJc7Fk8UGP0QHQq2uCEOrfbt.DFta/tGJDZN84skcNXUc/EWwL.',1,3,0,NULL,'2023-06-05 19:24:48','2023-08-23 06:38:34'),(5,NULL,'bjsc2@mail.com',NULL,'$2a$12$sjb9UjfMu4rU1g7bkZoxiuhePxKbpdRx5qLpCzFiLw.4AQC/uuFVK',1,3,1,NULL,'2023-06-05 19:24:48','2023-06-05 19:24:48'),(6,NULL,'bjsc3@mail.com',NULL,'$2a$12$sjb9UjfMu4rU1g7bkZoxiuhePxKbpdRx5qLpCzFiLw.4AQC/uuFVK',1,3,1,NULL,'2023-06-05 19:24:48','2023-06-05 19:24:48');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendors`
--

DROP TABLE IF EXISTS `vendors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cabang_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vendors_phone_unique` (`phone`),
  KEY `vendors_cabang_id_foreign` (`cabang_id`),
  CONSTRAINT `vendors_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendors`
--

LOCK TABLES `vendors` WRITE;
/*!40000 ALTER TABLE `vendors` DISABLE KEYS */;
INSERT INTO `vendors` VALUES (1,'BJSC','Mojokerto','08xxxxxxxxxx',2,'2023-06-05 19:24:48','2023-06-05 19:24:48');
/*!40000 ALTER TABLE `vendors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'dwhdev'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-09-05 10:06:42
