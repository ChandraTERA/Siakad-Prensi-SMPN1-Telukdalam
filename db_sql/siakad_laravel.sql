-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table siakad_laravel.absen_mandiri
CREATE TABLE IF NOT EXISTS `absen_mandiri` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_absen` time NOT NULL,
  `foto_absen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `guru_id` bigint unsigned DEFAULT NULL,
  `waktu_approval` timestamp NULL DEFAULT NULL,
  `keterangan_guru` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL COMMENT 'Koordinat latitude saat absen',
  `longitude` decimal(11,8) DEFAULT NULL COMMENT 'Koordinat longitude saat absen',
  `distance_meters` int DEFAULT NULL COMMENT 'Jarak dari sekolah dalam meter',
  `is_within_radius` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Apakah berada dalam radius sekolah',
  `gps_error` text COLLATE utf8mb4_unicode_ci COMMENT 'Error message jika GPS gagal',
  PRIMARY KEY (`id`),
  KEY `absen_mandiri_siswa_id_tanggal_index` (`siswa_id`,`tanggal`),
  KEY `absen_mandiri_status_tanggal_index` (`status`,`tanggal`),
  KEY `absen_mandiri_guru_id_waktu_approval_index` (`guru_id`,`waktu_approval`),
  CONSTRAINT `absen_mandiri_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `absen_mandiri_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.absen_mandiri: ~0 rows (approximately)
DELETE FROM `absen_mandiri`;

-- Dumping structure for table siakad_laravel.absen_mandiris
CREATE TABLE IF NOT EXISTS `absen_mandiris` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_absen` time NOT NULL,
  `foto_absen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','disetujui','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `disetujui_oleh` bigint unsigned DEFAULT NULL,
  `catatan_guru` text COLLATE utf8mb4_unicode_ci,
  `waktu_persetujuan` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `absen_mandiris_siswa_id_tanggal_unique` (`siswa_id`,`tanggal`),
  KEY `absen_mandiris_disetujui_oleh_foreign` (`disetujui_oleh`),
  CONSTRAINT `absen_mandiris_disetujui_oleh_foreign` FOREIGN KEY (`disetujui_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `absen_mandiris_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.absen_mandiris: ~0 rows (approximately)
DELETE FROM `absen_mandiris`;

-- Dumping structure for table siakad_laravel.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.cache: ~8 rows (approximately)
DELETE FROM `cache`;
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
	('672019153@student.uksw.edu|182.253.183.3', 'i:1;', 1766371884),
	('672019153@student.uksw.edu|182.253.183.3:timer', 'i:1766371884;', 1766371884),
	('chandra@gmail.com|118.99.73.5', 'i:1;', 1759808544),
	('chandra@gmail.com|118.99.73.5:timer', 'i:1759808544;', 1759808544),
	('chandrawpsiloto@gmaiil.com|182.2.50.156', 'i:1;', 1766373339),
	('chandrawpsiloto@gmaiil.com|182.2.50.156:timer', 'i:1766373339;', 1766373339),
	('chandrawpsiloto@gmail.com|118.99.73.27', 'i:1;', 1759294320),
	('chandrawpsiloto@gmail.com|118.99.73.27:timer', 'i:1759294320;', 1759294320);

-- Dumping structure for table siakad_laravel.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.cache_locks: ~0 rows (approximately)
DELETE FROM `cache_locks`;

-- Dumping structure for table siakad_laravel.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
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

-- Dumping data for table siakad_laravel.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table siakad_laravel.gurus
CREATE TABLE IF NOT EXISTS `gurus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telepon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gurus_nip_unique` (`nip`),
  KEY `gurus_user_id_foreign` (`user_id`),
  CONSTRAINT `gurus_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.gurus: ~2 rows (approximately)
DELETE FROM `gurus`;
INSERT INTO `gurus` (`id`, `user_id`, `nip`, `alamat`, `no_telepon`, `jabatan`, `created_at`, `updated_at`) VALUES
	(6, 22, '439439493', 'Noms Kopi Salatiga, Jl. Patimura No.60, Salatiga, Kec. Sidorejo, Kota Salatiga, Jawa Tengah 50711', '085361453786', 'guru', '2025-10-18 08:36:04', '2025-10-18 08:36:04'),
	(7, 25, '1829384736475867', 'Palembang', '082282076702', 'Guru', '2025-12-26 00:36:17', '2025-12-26 00:36:17');

-- Dumping structure for table siakad_laravel.guru_kelas
CREATE TABLE IF NOT EXISTS `guru_kelas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `kelas_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `guru_kelas_user_id_foreign` (`user_id`),
  KEY `guru_kelas_kelas_id_foreign` (`kelas_id`),
  CONSTRAINT `guru_kelas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `guru_kelas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.guru_kelas: ~2 rows (approximately)
DELETE FROM `guru_kelas`;
INSERT INTO `guru_kelas` (`id`, `user_id`, `kelas_id`, `created_at`, `updated_at`) VALUES
	(4, 22, 6, NULL, NULL),
	(6, 25, 6, NULL, NULL);

-- Dumping structure for table siakad_laravel.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.jobs: ~0 rows (approximately)
DELETE FROM `jobs`;

-- Dumping structure for table siakad_laravel.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.job_batches: ~0 rows (approximately)
DELETE FROM `job_batches`;

-- Dumping structure for table siakad_laravel.kelas
CREATE TABLE IF NOT EXISTS `kelas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tingkat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wali_kelas_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kelas_nama_kelas_unique` (`nama_kelas`),
  KEY `kelas_wali_kelas_id_foreign` (`wali_kelas_id`),
  CONSTRAINT `kelas_wali_kelas_id_foreign` FOREIGN KEY (`wali_kelas_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.kelas: ~1 rows (approximately)
DELETE FROM `kelas`;
INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkat`, `wali_kelas_id`, `created_at`, `updated_at`) VALUES
	(6, 'IPA - 2', 'XII', 25, '2025-10-07 04:28:12', '2025-12-26 00:37:42');

-- Dumping structure for table siakad_laravel.mata_pelajarans
CREATE TABLE IF NOT EXISTS `mata_pelajarans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_mapel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_mapel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mata_pelajarans_nama_mapel_unique` (`nama_mapel`),
  UNIQUE KEY `mata_pelajarans_kode_mapel_unique` (`kode_mapel`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.mata_pelajarans: ~15 rows (approximately)
DELETE FROM `mata_pelajarans`;
INSERT INTO `mata_pelajarans` (`id`, `nama_mapel`, `kode_mapel`, `created_at`, `updated_at`) VALUES
	(1, 'Matematika', 'MTK', '2025-09-27 03:45:58', '2025-09-27 03:45:58'),
	(2, 'Bahasa Indonesia', 'BIN', '2025-09-27 03:45:58', '2025-09-27 03:45:58'),
	(3, 'Bahasa Inggris', 'BIG', '2025-09-27 03:45:58', '2025-09-27 03:45:58'),
	(4, 'Fisika', 'FIS', '2025-09-27 03:45:58', '2025-09-27 03:45:58'),
	(5, 'Kimia', 'KIM', '2025-09-27 03:45:58', '2025-09-27 03:45:58'),
	(6, 'Biologi', 'BIO', '2025-09-27 03:45:58', '2025-09-27 03:45:58'),
	(7, 'Sejarah', 'SEJ', '2025-09-27 03:45:58', '2025-09-27 03:45:58'),
	(8, 'Geografi', 'GEO', '2025-09-27 03:45:58', '2025-09-27 03:45:58'),
	(9, 'Ekonomi', 'EKO', '2025-09-27 03:45:58', '2025-09-27 03:45:58'),
	(10, 'Sosiologi', 'SOS', '2025-09-27 03:45:58', '2025-09-27 03:45:58'),
	(11, 'Pendidikan Agama Islam', 'PAI', '2025-09-27 03:45:58', '2025-09-27 03:45:58'),
	(12, 'Pendidikan Kewarganegaraan', 'PKN', '2025-09-27 03:45:58', '2025-09-27 03:45:58'),
	(13, 'Seni Budaya', 'SB', '2025-09-27 03:45:58', '2025-09-27 03:45:58'),
	(14, 'Pendidikan Jasmani', 'PJOK', '2025-09-27 03:45:58', '2025-09-27 03:45:58'),
	(15, 'Teknologi Informasi dan Komunikasi', 'TIK', '2025-09-27 03:45:58', '2025-09-27 03:45:58');

-- Dumping structure for table siakad_laravel.materis
CREATE TABLE IF NOT EXISTS `materis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_materi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `mapel_id` bigint unsigned NOT NULL,
  `file_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` bigint unsigned DEFAULT NULL,
  `guru_id` bigint unsigned NOT NULL,
  `kelas_id` bigint unsigned NOT NULL,
  `link_size` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `materis_mapel_id_foreign` (`mapel_id`),
  KEY `materis_guru_id_foreign` (`guru_id`),
  KEY `materis_kelas_id_foreign` (`kelas_id`),
  CONSTRAINT `materis_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`),
  CONSTRAINT `materis_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`),
  CONSTRAINT `materis_mapel_id_foreign` FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajarans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.materis: ~0 rows (approximately)
DELETE FROM `materis`;

-- Dumping structure for table siakad_laravel.materi_downloads
CREATE TABLE IF NOT EXISTS `materi_downloads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `materi_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `downloaded_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `materi_downloads_materi_id_foreign` (`materi_id`),
  KEY `materi_downloads_siswa_id_foreign` (`siswa_id`),
  CONSTRAINT `materi_downloads_materi_id_foreign` FOREIGN KEY (`materi_id`) REFERENCES `materis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `materi_downloads_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.materi_downloads: ~0 rows (approximately)
DELETE FROM `materi_downloads`;

-- Dumping structure for table siakad_laravel.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.migrations: ~24 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_09_20_180433_create_gurus_table', 1),
	(5, '2025_09_21_043042_create_siswas_table', 1),
	(6, '2025_09_21_064141_create_kelas_table', 1),
	(7, '2025_09_21_064949_add_kelas_id_to_siswas_table', 1),
	(8, '2025_09_21_082831_create_guru_kelas_table', 1),
	(9, '2025_09_21_142237_create_presensi_table', 1),
	(10, '2025_09_21_150548_add_jenis_kelamin_to_siswas_table', 2),
	(11, '2025_09_21_173659_add_profile_photo_path_to_users_table', 3),
	(12, '2025_09_21_174630_create_absen_mandiris_table', 4),
	(15, '2025_09_26_171033_create_tugas_table', 5),
	(16, '2025_09_26_171107_create_pengumpulan_tugas_table', 5),
	(17, '2025_09_27_084109_create_mata_pelajarans_table', 6),
	(18, '2025_09_27_084229_create_materis_table', 6),
	(19, '2025_09_27_084530_create_materi_downloads_table', 7),
	(20, '2025_09_28_053244_create_presensi_sessions_table', 8),
	(21, '2025_09_28_055847_create_absen_mandiri_table', 9),
	(22, '2025_09_28_060225_modify_absen_mandiri_foto_absen_nullable', 10),
	(23, '2025_09_28_084620_create_notifications_table', 11),
	(24, '2025_09_28_091805_create_presensi_statistics_table', 12),
	(25, '2025_09_28_114349_create_school_locations_table', 13),
	(26, '2025_09_28_114905_add_gps_fields_to_absen_mandiri_table', 14);

-- Dumping structure for table siakad_laravel.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_is_read_index` (`user_id`,`is_read`),
  KEY `notifications_user_id_created_at_index` (`user_id`,`created_at`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notifications_chk_1` CHECK (json_valid(`data`))
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.notifications: ~0 rows (approximately)
DELETE FROM `notifications`;
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `data`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
	(131, 22, 'presensi_verification', 'Presensi Mandiri Baru', 'Siswa Fahrul Adib telah mengirim presensi mandiri yang perlu diverifikasi.', '{"absen_id":16,"siswa_name":"Fahrul Adib","kelas_name":"IPA - 2"}', 0, NULL, '2026-03-06 10:36:11', '2026-03-06 10:36:11'),
	(132, 25, 'presensi_verification', 'Presensi Mandiri Baru', 'Siswa Fahrul Adib telah mengirim presensi mandiri yang perlu diverifikasi.', '{"absen_id":16,"siswa_name":"Fahrul Adib","kelas_name":"IPA - 2"}', 0, NULL, '2026-03-06 10:36:11', '2026-03-06 10:36:11'),
	(133, 24, 'presensi_verification', 'Status Presensi Mandiri', 'Presensi mandiri Anda telah disetujui oleh Sudendev.', '{"absen_id":16,"status":"approved","guru_name":"Sudendev","keterangan":null}', 0, NULL, '2026-03-06 10:36:43', '2026-03-06 10:36:43'),
	(134, 22, 'presensi_verification', 'Presensi Mandiri Baru', 'Siswa Fahrul Adib telah mengirim presensi mandiri yang perlu diverifikasi.', '{"absen_id":17,"siswa_name":"Fahrul Adib","kelas_name":"IPA - 2"}', 0, NULL, '2026-03-06 10:37:43', '2026-03-06 10:37:43'),
	(135, 25, 'presensi_verification', 'Presensi Mandiri Baru', 'Siswa Fahrul Adib telah mengirim presensi mandiri yang perlu diverifikasi.', '{"absen_id":17,"siswa_name":"Fahrul Adib","kelas_name":"IPA - 2"}', 0, NULL, '2026-03-06 10:37:43', '2026-03-06 10:37:43');

-- Dumping structure for table siakad_laravel.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.password_reset_tokens: ~2 rows (approximately)
DELETE FROM `password_reset_tokens`;
INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
	('Chandrawpsiloto@gmail.com', '$2y$12$oyIGR4ManJBYhOzxTrCoS.vykPffxQn/yHcQ8446JAJ.TCZKqPJCe', '2025-12-22 03:15:11'),
	('guru@gmail.com', '$2y$12$mN1hzpH65kjoqcPvO9NfiO2oiGKmNEVOfvxssfFF7VvXMjfQWBfcq', '2025-09-28 01:32:43');

-- Dumping structure for table siakad_laravel.pengumpulan_tugas
CREATE TABLE IF NOT EXISTS `pengumpulan_tugas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tugas_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `jawaban` text COLLATE utf8mb4_unicode_ci,
  `file_submission` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('terlambat','tepat_waktu') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tepat_waktu',
  `nilai` int DEFAULT NULL,
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pengumpulan_tugas_tugas_id_siswa_id_unique` (`tugas_id`,`siswa_id`),
  KEY `pengumpulan_tugas_tugas_id_status_index` (`tugas_id`,`status`),
  KEY `pengumpulan_tugas_siswa_id_index` (`siswa_id`),
  CONSTRAINT `pengumpulan_tugas_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pengumpulan_tugas_tugas_id_foreign` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.pengumpulan_tugas: ~2 rows (approximately)
DELETE FROM `pengumpulan_tugas`;
INSERT INTO `pengumpulan_tugas` (`id`, `tugas_id`, `siswa_id`, `jawaban`, `file_submission`, `file_name`, `submitted_at`, `status`, `nilai`, `feedback`, `created_at`, `updated_at`) VALUES
	(5, 10, 23, NULL, 'submissions/23/B0IW9qp6jQApqH1dvaFdoI8L9JjOCdMFCsM5jbzE.png', 'ChatGPT Image 8 Des 2025, 22.05.40.png', '2025-12-21 15:59:49', 'tepat_waktu', 4, 'e', '2025-12-21 15:59:39', '2025-12-21 15:59:49'),
	(6, 11, 23, 's', 'submissions/23/wmiQlaCtJm0NQUuRqft36Fw3wQ7hFdUafz9DqhV2.png', 'ChatGPT Image 6 Nov 2025, 04.21.09.png', '2025-12-22 03:02:08', 'tepat_waktu', NULL, NULL, '2025-12-22 03:02:08', '2025-12-22 03:02:08');

-- Dumping structure for table siakad_laravel.presensi
CREATE TABLE IF NOT EXISTS `presensi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint unsigned NOT NULL,
  `guru_id` bigint unsigned NOT NULL,
  `kelas_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('hadir','izin','sakit','alpa') COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `presensi_siswa_id_foreign` (`siswa_id`),
  KEY `presensi_guru_id_foreign` (`guru_id`),
  KEY `presensi_kelas_id_foreign` (`kelas_id`),
  CONSTRAINT `presensi_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `presensi_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `presensi_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.presensi: ~0 rows (approximately)
DELETE FROM `presensi`;
INSERT INTO `presensi` (`id`, `siswa_id`, `guru_id`, `kelas_id`, `tanggal`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
	(50, 24, 25, 6, '2026-03-06', 'hadir', NULL, '2026-03-06 10:38:50', '2026-03-06 10:38:50'),
	(51, 12, 25, 6, '2026-03-06', 'hadir', NULL, '2026-03-06 10:38:50', '2026-03-06 10:38:50'),
	(52, 23, 25, 6, '2026-03-06', 'hadir', NULL, '2026-03-06 10:38:50', '2026-03-06 10:38:50');

-- Dumping structure for table siakad_laravel.presensi_sessions
CREATE TABLE IF NOT EXISTS `presensi_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `guru_id` bigint unsigned NOT NULL,
  `kelas_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_mulai` time DEFAULT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `status` enum('buka','tutup') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tutup',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `auto_close_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `presensi_sessions_kelas_id_foreign` (`kelas_id`),
  KEY `presensi_sessions_guru_id_kelas_id_tanggal_index` (`guru_id`,`kelas_id`,`tanggal`),
  KEY `presensi_sessions_status_tanggal_index` (`status`,`tanggal`),
  CONSTRAINT `presensi_sessions_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `presensi_sessions_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.presensi_sessions: ~15 rows (approximately)
DELETE FROM `presensi_sessions`;
INSERT INTO `presensi_sessions` (`id`, `guru_id`, `kelas_id`, `tanggal`, `waktu_mulai`, `waktu_selesai`, `status`, `keterangan`, `auto_close_at`, `created_at`, `updated_at`) VALUES
	(11, 22, 6, '2025-10-18', '08:38:44', NULL, 'buka', NULL, NULL, '2025-10-18 08:38:44', '2025-10-18 08:38:44'),
	(12, 22, 6, '2025-12-21', '14:38:20', '14:38:58', 'tutup', NULL, NULL, '2025-12-21 14:38:20', '2025-12-21 14:38:58'),
	(13, 22, 6, '2025-12-21', '15:21:37', '15:35:25', 'tutup', NULL, NULL, '2025-12-21 15:21:37', '2025-12-21 15:35:26'),
	(14, 22, 6, '2025-12-21', '15:38:00', '15:43:44', 'tutup', NULL, NULL, '2025-12-21 15:38:00', '2025-12-21 15:43:44'),
	(15, 22, 6, '2025-12-21', '16:01:44', '16:09:02', 'tutup', NULL, NULL, '2025-12-21 16:01:44', '2025-12-21 16:09:02'),
	(16, 22, 6, '2025-12-21', '16:09:06', '16:12:19', 'tutup', NULL, NULL, '2025-12-21 16:09:06', '2025-12-21 16:12:19'),
	(17, 22, 6, '2025-12-22', '02:48:43', '02:58:39', 'tutup', NULL, NULL, '2025-12-22 02:48:43', '2025-12-22 02:58:39'),
	(18, 22, 6, '2025-12-22', '03:08:39', '03:11:18', 'tutup', NULL, NULL, '2025-12-22 03:08:39', '2025-12-22 03:11:18'),
	(19, 22, 6, '2025-12-22', '03:18:34', '03:19:08', 'tutup', NULL, NULL, '2025-12-22 03:18:34', '2025-12-22 03:19:08'),
	(20, 22, 6, '2025-12-22', '03:19:30', '03:20:08', 'tutup', NULL, NULL, '2025-12-22 03:19:30', '2025-12-22 03:20:08'),
	(21, 22, 6, '2025-12-22', '03:25:13', '09:05:43', 'tutup', NULL, NULL, '2025-12-22 03:25:13', '2025-12-22 09:05:43'),
	(22, 22, 6, '2025-12-22', '09:07:56', '09:13:00', 'tutup', NULL, NULL, '2025-12-22 09:07:56', '2025-12-22 09:13:00'),
	(25, 25, 6, '2025-12-26', '14:42:56', '15:06:23', 'tutup', NULL, NULL, '2025-12-26 07:42:56', '2025-12-26 08:06:23'),
	(26, 25, 6, '2025-12-27', '16:08:12', NULL, 'buka', NULL, NULL, '2025-12-27 09:08:12', '2025-12-27 09:08:12'),
	(27, 25, 6, '2026-03-06', '10:02:57', NULL, 'buka', NULL, NULL, '2026-03-06 03:02:57', '2026-03-06 03:02:57');

-- Dumping structure for table siakad_laravel.presensi_statistics
CREATE TABLE IF NOT EXISTS `presensi_statistics` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_id` bigint unsigned NOT NULL,
  `guru_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `periode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_siswa` int NOT NULL,
  `hadir` int NOT NULL,
  `izin` int NOT NULL,
  `sakit` int NOT NULL,
  `alpa` int NOT NULL,
  `persentase_hadir` decimal(5,2) NOT NULL DEFAULT '0.00',
  `persentase_izin` decimal(5,2) NOT NULL DEFAULT '0.00',
  `persentase_sakit` decimal(5,2) NOT NULL DEFAULT '0.00',
  `persentase_alpa` decimal(5,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `presensi_statistics_kelas_id_guru_id_tanggal_periode_unique` (`kelas_id`,`guru_id`,`tanggal`,`periode`),
  KEY `presensi_statistics_kelas_id_tanggal_index` (`kelas_id`,`tanggal`),
  KEY `presensi_statistics_guru_id_tanggal_index` (`guru_id`,`tanggal`),
  KEY `presensi_statistics_periode_tanggal_index` (`periode`,`tanggal`),
  CONSTRAINT `presensi_statistics_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `presensi_statistics_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.presensi_statistics: ~1 rows (approximately)
DELETE FROM `presensi_statistics`;
INSERT INTO `presensi_statistics` (`id`, `kelas_id`, `guru_id`, `tanggal`, `periode`, `total_siswa`, `hadir`, `izin`, `sakit`, `alpa`, `persentase_hadir`, `persentase_izin`, `persentase_sakit`, `persentase_alpa`, `created_at`, `updated_at`) VALUES
	(20, 6, 25, '2026-03-06', 'daily', 3, 3, 0, 0, 0, 100.00, 0.00, 0.00, 0.00, '2026-03-06 04:22:52', '2026-03-06 10:38:55');

-- Dumping structure for table siakad_laravel.school_locations
CREATE TABLE IF NOT EXISTS `school_locations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_lokasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama lokasi sekolah',
  `latitude` decimal(10,8) NOT NULL COMMENT 'Koordinat latitude',
  `longitude` decimal(11,8) NOT NULL COMMENT 'Koordinat longitude',
  `radius_meter` int NOT NULL DEFAULT '100' COMMENT 'Radius dalam meter',
  `alamat` text COLLATE utf8mb4_unicode_ci COMMENT 'Alamat lengkap sekolah',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Status aktif lokasi',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.school_locations: ~1 rows (approximately)
DELETE FROM `school_locations`;
INSERT INTO `school_locations` (`id`, `nama_lokasi`, `latitude`, `longitude`, `radius_meter`, `alamat`, `is_active`, `created_at`, `updated_at`) VALUES
	(5, 'SMA Negeri 1 Salatiga', -2.99417799, 104.74294639, 100, 'Jl. Patimura No.62, Salatiga, Kec. Sidorejo, Kota Salatiga, Jawa Tengah 50711', 1, '2025-10-12 10:19:05', '2025-12-26 00:34:25');

-- Dumping structure for table siakad_laravel.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.sessions: ~1 rows (approximately)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('mXsiBPp4oJGOMpvaDL2bDztQRSkR9G2Mmn0B4LLX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJbHBPYzIxMmIzaG5lRVJDUm5wblpXVjVWVkpHWldjOVBTSXNJblpoYkhWbElqb2lNMUo0VUhOSFduUXdiMjFCWWs5U1oySm5aek5WWTFOR1NIY3ZiVTlxVlZOdFp6ZGlkRm95TUZKM1NISmtUVEZRTDNCUE9FTXZSM2w2ZFZacWFuQk5WV3hTSzBGTVNEVnphRmRZVW1oQmFFUlZTV3c0TkU1dWVHSkZjWGxxZEVWRmNXOXRjbk5yYUhCNmRGVTFNSFZHVURaMlkyOXZVVVZpVFZneVZtNUpZa2haUTNSR2QxcGxNbnB5YW1OdlZWUkhkRTVQYmpKNGRqVTJiM1JZYmt4Mk5UaFBiRkl6T0ZGemJFRkJhRE4xYkVwblVUSk1OM0pPY0RGRFJtTnlZbnBEV0VReGVESkVTVTlUVUdjMFNUZDNkRk5MYm1WSVNreGxSemRKVERWRlQwMVZMMkUxWjAweldUVTJjR05ITUVjMGJsWmhibEEyVFdOck9IbzRRMWhQTkNJc0ltMWhZeUk2SW1FeFlUUTJaV1V3TldFd1ltVTFaV0l5WkRNd1lUWmlPREExTWpNeVl6Y3lObVl3Wm1VMFpUTmxPREk0WWpZMVltRXlNbVpsTUdZNE5qTXlaR05pWkRFaUxDSjBZV2NpT2lJaWZRPT0=', 1772793612);

-- Dumping structure for table siakad_laravel.siswas
CREATE TABLE IF NOT EXISTS `siswas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `kelas_id` bigint unsigned DEFAULT NULL,
  `nis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_orang_tua` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kontak_orang_tua` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `siswas_user_id_foreign` (`user_id`),
  KEY `siswas_kelas_id_foreign` (`kelas_id`),
  CONSTRAINT `siswas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `siswas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.siswas: ~10 rows (approximately)
DELETE FROM `siswas`;
INSERT INTO `siswas` (`id`, `user_id`, `kelas_id`, `nis`, `jenis_kelamin`, `alamat`, `nama_orang_tua`, `kontak_orang_tua`, `created_at`, `updated_at`) VALUES
	(1, 3, NULL, '20240101', 'L', 'Alamat Siswa 1', 'Orang Tua Siswa 1', '08123456701', '2025-09-21 08:23:43', '2025-09-21 08:23:43'),
	(2, 5, NULL, '20240102', 'L', 'Alamat Siswa 2', 'Orang Tua Siswa 2', '08123456702', '2025-09-21 08:23:44', '2025-09-21 08:23:44'),
	(3, 6, NULL, '20240103', 'L', 'Alamat Siswa 3', 'Orang Tua Siswa 3', '08123456703', '2025-09-21 08:23:44', '2025-09-21 08:23:44'),
	(4, 7, NULL, '20240104', 'L', 'Alamat Siswa 4', 'Orang Tua Siswa 4', '08123456704', '2025-09-21 08:23:44', '2025-09-21 08:23:44'),
	(6, 9, NULL, '20240201', 'L', 'Alamat Siswa 1', 'Orang Tua Siswa 1', '08123456801', '2025-09-21 08:23:45', '2025-09-21 08:23:45'),
	(7, 10, NULL, '20240202', 'L', 'Alamat Siswa 2', 'Orang Tua Siswa 2', '08123456802', '2025-09-21 08:23:45', '2025-09-21 08:23:45'),
	(8, 11, NULL, '20240203', 'L', 'Alamat Siswa 3', 'Orang Tua Siswa 3', '08123456803', '2025-09-21 08:23:45', '2025-09-21 08:23:45'),
	(9, 12, 6, '132233', 'L', 'Jl H Muyan\r\nBekasi', 'Budi', '081335938594', '2025-09-28 01:17:40', '2025-10-07 04:30:11'),
	(14, 23, 6, '45454', 'L', 'Noms Kopi Salatiga, Jl. Patimura No.60', 'fddfsd', 'ddd', '2025-12-21 15:23:27', '2025-12-21 15:23:27'),
	(15, 24, 6, '12313123213', 'L', 'Palembang', 'Anton', '082738827382', '2025-12-26 00:35:11', '2025-12-26 00:35:11');

-- Dumping structure for table siakad_laravel.tugas
CREATE TABLE IF NOT EXISTS `tugas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `guru_id` bigint unsigned NOT NULL,
  `kelas_id` bigint unsigned NOT NULL,
  `deadline` date NOT NULL,
  `waktu_deadline` time DEFAULT NULL,
  `file_attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('aktif','selesai','dibatalkan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tugas_kelas_id_foreign` (`kelas_id`),
  KEY `tugas_guru_id_kelas_id_index` (`guru_id`,`kelas_id`),
  KEY `tugas_deadline_index` (`deadline`),
  CONSTRAINT `tugas_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tugas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.tugas: ~3 rows (approximately)
DELETE FROM `tugas`;
INSERT INTO `tugas` (`id`, `judul`, `deskripsi`, `guru_id`, `kelas_id`, `deadline`, `waktu_deadline`, `file_attachment`, `file_name`, `status`, `created_at`, `updated_at`) VALUES
	(10, 'Deploy', 'w', 22, 6, '2025-12-22', '12:59:00', 'assignments/1766332758_INSTAGRAM-2FA-RecoveryCodes.txt', 'INSTAGRAM-2FA-RecoveryCodes.txt', 'aktif', '2025-12-21 15:59:18', '2025-12-21 15:59:18'),
	(11, 'deploymen UIUX', 'a', 22, 6, '2025-12-23', '11:01:00', 'assignments/1766372503_INSTAGRAM-2FA-RecoveryCodes.txt', 'INSTAGRAM-2FA-RecoveryCodes.txt', 'aktif', '2025-12-22 03:01:43', '2025-12-22 03:01:43'),
	(13, 'Pengertian Lalu Lintas dan Pelanggaran Lalu Lintas', 'sadasdads', 25, 6, '2026-03-07', '19:11:00', NULL, NULL, 'aktif', '2025-12-27 09:11:16', '2026-03-06 10:39:43');

-- Dumping structure for table siakad_laravel.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','guru','siswa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_nip_unique` (`nip`),
  UNIQUE KEY `users_nis_unique` (`nis`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad_laravel.users: ~13 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `profile_photo_path`, `email_verified_at`, `role`, `nip`, `nis`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin User', 'admin@gmail.com', NULL, NULL, 'admin', NULL, NULL, '$2y$12$.LozkbJ7z.YwnIsdxv.pa.odU7MfPxNY5i8CvEltUU1.94RJ/qp9e', 'NZaOjgBQst3r9JZVdRnCXq4vbfVJCdRlCEjQEjvbWYvrDIufz8lP0DOC3BQb', '2025-09-21 07:27:14', '2025-09-28 19:42:53'),
	(3, 'Siswa Rajin', 'siswa@gmail.com', NULL, NULL, 'siswa', NULL, '2025001', '$2y$12$sBnxiCgflO4pLZE1feAF6eKmTydY97ZOvdyRtC51i89L8SWl.gf4y', NULL, '2025-09-21 07:27:15', '2025-09-28 19:42:53'),
	(5, 'Siswa 2 Kelas X-IPA-1', 'siswa2xipa1@test.com', NULL, NULL, 'siswa', NULL, NULL, '$2y$12$7MEKfnCftWbUhk3n9d2kX.Ri8PmzEI4vxpQToFg/Wtv4W8y.MyIrm', NULL, '2025-09-21 08:23:44', '2025-09-28 19:42:54'),
	(6, 'Siswa 3 Kelas X-IPA-1', 'siswa3xipa1@test.com', NULL, NULL, 'siswa', NULL, NULL, '$2y$12$ONVvqRiAQA68rLxtyUxCbeyCfmrf9TFU07HBKci7ed.zan7HNoK4e', NULL, '2025-09-21 08:23:44', '2025-09-28 19:42:54'),
	(7, 'Siswa 4 Kelas X-IPA-1', 'siswa4xipa1@test.com', NULL, NULL, 'siswa', NULL, NULL, '$2y$12$j7UR0N6CkyfgZ4MBRbhWQ.v9myvpJs..WBJ.dO/uhn.mNJRnZ0Zui', NULL, '2025-09-21 08:23:44', '2025-09-28 19:42:54'),
	(9, 'Siswa 1 Kelas X-IPA-2', 'siswa1xipa2@test.com', NULL, NULL, 'siswa', NULL, NULL, '$2y$12$mL1h9KixFsKFbBviagXTsO0W8aH0aiUISqRkAAt19l59Q2.kiPrA2', NULL, '2025-09-21 08:23:45', '2025-09-28 19:42:55'),
	(10, 'Siswa 2 Kelas X-IPA-2', 'siswa2xipa2@test.com', NULL, NULL, 'siswa', NULL, NULL, '$2y$12$IuFiP5cRK7McI8GiNlChe.SbBph3xgKHNXeK1eiJ.Dc19GfSDguhe', NULL, '2025-09-21 08:23:45', '2025-09-28 19:42:55'),
	(11, 'Siswa 3 Kelas X-IPA-2', 'siswa3xipa2@test.com', NULL, NULL, 'siswa', NULL, NULL, '$2y$12$4oYErb8bdGHg1pnUuLuxn.zpbJpgeJjbenYnlKk4nX3EcAboEFCOu', NULL, '2025-09-21 08:23:45', '2025-09-28 19:42:55'),
	(12, 'Rio Anggoro', 'rio@gmail.com', NULL, NULL, 'siswa', NULL, NULL, '$2y$12$evlsAl4Nr0tZzQH4eqyukeoTL1lfDWZqpghCQha6pC3c7aK3qLCzm', '9LSWvZgU0m5Rm2JPCpvWpwb9CXcMloAq1HmYhD4OYRZSSntG3K3xNi3lgliN', '2025-09-28 01:17:40', '2025-10-18 08:33:26'),
	(22, 'Chandra Tera', 'Chandratiestto@gmail.com', NULL, NULL, 'guru', NULL, NULL, '$2y$12$02HyWqjgYsvgzUy3aIocSusEaeNDq3Bko7Kq6DjpTCSD2RckGHTCm', '9hAbZdw1t2oB9fz4DsgYajBUWx1WPfgNr5CPnhGrJMmpnbvKh41ltZyY6d5H', '2025-10-18 08:36:04', '2025-12-21 14:36:18'),
	(23, 'Salah benar', 'Chandrawpsiloto@gmail.com', NULL, NULL, 'siswa', NULL, NULL, '$2y$12$ADvVG4gTMHJNOl20YZyGkOA71f.lVSWWiP.CDAYiuyuDhLp/eTkry', 'xIFxDeWpI35yFfCf2UsnSrkwLHd6isRJkEB2MvN1QK3Kiv0nl74EgP9twdDB', '2025-12-21 15:23:27', '2025-12-21 15:23:27'),
	(24, 'Fahrul Adib', 'fahruladib9@gmail.com', NULL, NULL, 'siswa', NULL, NULL, '$2y$12$xFQtyQRlVtQRGMTT2rs0yeWoMDFl3qTdJBoqZmO7kfo3arKEjnO6a', NULL, '2025-12-26 00:35:11', '2025-12-26 00:35:11'),
	(25, 'Sudendev', 'sudendev@gmail.com', NULL, NULL, 'guru', NULL, NULL, '$2y$12$O...SZJ5zUHdFFjxdbR.ouW4Rx.MloAYSUnekh4PSMj/wgWwg.E92', NULL, '2025-12-26 00:36:17', '2025-12-26 00:36:17');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
