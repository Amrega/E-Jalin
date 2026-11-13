-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Nov 2026 pada 09.15
-- Versi server: 10.11.16-MariaDB
-- Versi PHP: 8.3.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `ejalin`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jembatan`
--

CREATE TABLE `jembatan` (
  `id` int(11) NOT NULL,
  `ruas_jalan_id` int(11) NOT NULL,
  `nama_jembatan` varchar(255) NOT NULL,
  `nomor_jembatan` varchar(100) DEFAULT NULL,
  `panjang_jembatan` double DEFAULT NULL,
  `status_jembatan` enum('Kabupaten/Kota','Non Status') NOT NULL DEFAULT 'Kabupaten/Kota',
  `file_kml_jembatan` varchar(255) DEFAULT NULL,
  `void` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jembatan`
--

INSERT INTO `jembatan` (`id`, `ruas_jalan_id`, `nama_jembatan`, `nomor_jembatan`, `panjang_jembatan`, `status_jembatan`, `file_kml_jembatan`, `void`, `created_at`, `updated_at`) VALUES
(1, 1, 'Jembatan Dawung I', '28.001.01.0', 38, 'Kabupaten/Kota', 'jembatan_geo_1.kml', 0, '2026-11-13 02:02:14', '2026-11-12 22:43:58'),
(2, 2, 'raws', 'raws', 4.7, 'Kabupaten/Kota', '1794535523_doc.kml', 1, '2026-11-12 19:05:23', '2026-11-13 05:24:22'),
(3, 20, 'Jembatan Suramadu Sisi Madura', 'JMB-01-SRM', 5438, 'Kabupaten/Kota', 'jembatan_geo_3.kml', 0, '2026-11-12 22:15:59', '2026-11-12 22:43:58'),
(4, 8, 'Jembatan Kali Porong Baru', 'JMB-02-PRG', 185, 'Kabupaten/Kota', 'jembatan_geo_4.kml', 0, '2026-11-12 22:15:59', '2026-11-12 22:43:58'),
(5, 16, 'Jembatan Kali Brantas Kertosono', 'JMB-03-KTS', 210, 'Kabupaten/Kota', 'jembatan_geo_5.kml', 0, '2026-11-12 22:15:59', '2026-11-12 22:43:59'),
(6, 17, 'Jembatan Ploso Baru', 'JMB-04-PLS', 258, 'Kabupaten/Kota', 'jembatan_geo_6.kml', 0, '2026-11-12 22:15:59', '2026-11-12 22:43:59'),
(7, 12, 'Jembatan Gladak Perak Reborn', 'JMB-05-GLP', 140, 'Kabupaten/Kota', 'jembatan_geo_7.kml', 0, '2026-11-12 22:15:59', '2026-11-12 22:43:59'),
(8, 4, 'Jembatan Brawijaya Kediri', 'JMB-06-BWJ', 175, 'Kabupaten/Kota', 'jembatan_geo_8.kml', 0, '2026-11-12 22:16:00', '2026-11-12 22:43:59'),
(9, 6, 'Jembatan Bengawan Solo Babat', 'JMB-07-BBT', 240, 'Kabupaten/Kota', 'jembatan_geo_9.kml', 0, '2026-11-12 22:16:00', '2026-11-12 22:43:59'),
(10, 17, 'Jembatan Kali Sadar Mojokerto', 'JMB-08-SDR', 65, 'Kabupaten/Kota', 'jembatan_geo_10.kml', 0, '2026-11-12 22:16:00', '2026-11-12 22:43:59'),
(11, 18, 'Jembatan Grindulu Pacitan', 'JMB-09-GRD', 320, 'Kabupaten/Kota', 'jembatan_geo_11.kml', 0, '2026-11-12 22:16:00', '2026-11-12 22:43:59'),
(12, 13, 'Jembatan Kali Sampean Situbondo', 'JMB-10-SMP', 95, 'Kabupaten/Kota', 'jembatan_geo_12.kml', 0, '2026-11-12 22:16:00', '2026-11-12 22:43:59'),
(13, 14, 'Jembatan Blimbingsari Banyuwangi', 'JMB-11-BLM', 75, 'Kabupaten/Kota', 'jembatan_geo_13.kml', 0, '2026-11-12 22:16:00', '2026-11-12 22:43:59'),
(14, 4, 'Jembatan Gandong Tulungagung', 'JMB-12-GDG', 52, 'Kabupaten/Kota', 'jembatan_geo_14.kml', 0, '2026-11-12 22:16:00', '2026-11-12 22:43:59'),
(15, 7, 'Jembatan Sembayat Baru Gresik', 'JMB-13-SBY', 380, 'Kabupaten/Kota', 'jembatan_geo_15.kml', 0, '2026-11-12 22:16:00', '2026-11-12 22:43:59'),
(16, 21, 'Jembatan Kali Sampang', 'JMB-14-SPG', 88, 'Kabupaten/Kota', 'jembatan_geo_16.kml', 0, '2026-11-12 22:16:00', '2026-11-12 22:43:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_penanganan`
--

CREATE TABLE `jenis_penanganan` (
  `id` int(11) NOT NULL,
  `target_penangan` enum('jalan','jembatan') NOT NULL,
  `nama_penanganan` varchar(255) NOT NULL,
  `void` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jenis_penanganan`
--

INSERT INTO `jenis_penanganan` (`id`, `target_penangan`, `nama_penanganan`, `void`, `created_at`, `updated_at`) VALUES
(1, 'jalan', 'Pemeliharaan Rutin Jalan', 0, '2026-11-12 15:00:20', '2026-11-12 15:00:20'),
(2, 'jalan', 'Pemeliharaan Berkala Jalan', 0, '2026-11-12 15:00:20', '2026-11-12 15:00:20'),
(3, 'jalan', 'Rehabilitasi Jalan', 0, '2026-11-12 15:00:20', '2026-11-12 15:00:20'),
(4, 'jalan', 'Rekonstruksi Jalan', 0, '2026-11-12 15:00:20', '2026-11-12 15:00:20'),
(5, 'jalan', 'Pelebaran Jalan Menuju Standar', 0, '2026-11-12 15:00:20', '2026-11-12 15:00:20'),
(6, 'jembatan', 'Pemeliharaan Rutin Jembatan', 0, '2026-11-12 15:00:20', '2026-11-12 15:00:20'),
(7, 'jembatan', 'Pemeliharaan Berkala Jembatan', 0, '2026-11-12 15:00:20', '2026-11-12 15:00:20'),
(8, 'jembatan', 'Rehabilitasi Jembatan', 0, '2026-11-12 15:00:20', '2026-11-12 15:00:20'),
(9, 'jembatan', 'Penggantian Jembatan', 0, '2026-11-12 15:00:20', '2026-11-12 15:00:20'),
(10, 'jembatan', 'Pelebaran dan Duplikasi Jembatan', 0, '2026-11-12 15:00:20', '2026-11-12 15:00:20'),
(11, 'jembatan', 'Perbaikan dan Penanganan Sementara', 0, '2026-11-12 15:00:20', '2026-11-12 15:00:20'),
(14, 'jembatan', 'tes', 0, '2026-11-12 21:36:51', '2026-11-12 21:36:51');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kabupaten_kota`
--

CREATE TABLE `kabupaten_kota` (
  `id` int(11) NOT NULL,
  `provinsi_id` int(11) NOT NULL,
  `upt_id` int(11) DEFAULT NULL,
  `nama_kabupaten_kota` varchar(100) NOT NULL,
  `void` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kabupaten_kota`
--

INSERT INTO `kabupaten_kota` (`id`, `provinsi_id`, `upt_id`, `nama_kabupaten_kota`, `void`, `created_at`, `updated_at`) VALUES
(1, 1, 11, 'Kabupaten Bangkalan', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:42'),
(2, 1, 10, 'Kabupaten Banyuwangi', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:42'),
(3, 1, 6, 'Kabupaten Blitar', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(4, 1, 3, 'Kabupaten Bojonegoro', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(5, 1, 9, 'Kabupaten Bondowoso', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:42'),
(6, 1, 1, 'Kabupaten Gresik', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(7, 1, 9, 'Kabupaten Jember', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:42'),
(8, 1, 2, 'Kabupaten Jombang', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(9, 1, 6, 'Kabupaten Kediri', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(10, 1, 1, 'Kabupaten Lamongan', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(11, 1, 8, 'Kabupaten Lumajang', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:42'),
(12, 1, 4, 'Kabupaten Madiun', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(13, 1, 4, 'Kabupaten Magetan', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(14, 1, 7, 'Kabupaten Malang', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:42'),
(15, 1, 2, 'Kabupaten Mojokerto', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(16, 1, 6, 'Kabupaten Nganjuk', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(17, 1, 4, 'Kabupaten Ngawi', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(18, 1, 5, 'Kabupaten Pacitan', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(19, 1, 11, 'Kabupaten Pamekasan', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:42'),
(20, 1, 8, 'Kabupaten Pasuruan', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:42'),
(21, 1, 4, 'Kabupaten Ponorogo', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(22, 1, 8, 'Kabupaten Probolinggo', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:42'),
(23, 1, 11, 'Kabupaten Sampang', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:42'),
(24, 1, 1, 'Kabupaten Sidoarjo', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(25, 1, 10, 'Kabupaten Situbondo', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:42'),
(26, 1, 11, 'Kabupaten Sumenep', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:42'),
(27, 1, 4, 'Kabupaten Trenggalek', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(28, 1, 3, 'Kabupaten Tuban', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(29, 1, 6, 'Kabupaten Tulungagung', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(30, 1, 7, 'Kota Batu', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:42'),
(31, 1, 6, 'Kota Blitar', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(32, 1, 6, 'Kota Kediri', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(33, 1, 4, 'Kota Madiun', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(34, 1, 7, 'Kota Malang', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:42'),
(35, 1, 2, 'Kota Mojokerto', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41'),
(36, 1, 8, 'Kota Pasuruan', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:42'),
(37, 1, 8, 'Kota Probolinggo', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:42'),
(38, 1, 1, 'Kota Surabaya', 0, '2026-11-12 13:20:02', '2026-11-12 15:33:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `koridor`
--

CREATE TABLE `koridor` (
  `id` int(11) NOT NULL,
  `kabupaten_kota_id` int(11) NOT NULL,
  `nama_koridor` varchar(255) NOT NULL,
  `nomor_koridor` varchar(100) DEFAULT NULL,
  `panjang_koridor` double DEFAULT NULL,
  `file_kml_koridor` varchar(255) DEFAULT NULL,
  `void` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `koridor`
--

INSERT INTO `koridor` (`id`, `kabupaten_kota_id`, `nama_koridor`, `nomor_koridor`, `panjang_koridor`, `file_kml_koridor`, `void`, `created_at`, `updated_at`) VALUES
(1, 1, 'tes', 'tes', 30.5, '1794471188_doc.kml', 1, '2026-11-12 01:07:47', '2026-11-13 05:24:22'),
(2, 2, 'Koridor 08: Lingkar Ijen - Banyuwangi (Ketapang - Baluran - Ijen - Rogojampi)', 'KOR-08', 95.6, 'koridor_geo_2.kml', 0, '2026-11-12 17:28:11', '2026-11-12 22:41:12'),
(3, 28, 'Koridor 01: Pantura Jatim (Tuban - Lamongan - Gresik - Surabaya)', 'KOR-01', 124.5, 'koridor_geo_3.kml', 0, '2026-11-12 22:15:57', '2026-11-12 22:41:12'),
(4, 18, 'Koridor 02: Pansela Jatim (Pacitan - Trenggalek - Tulungagung)', 'KOR-02', 148.8, 'koridor_geo_4.kml', 0, '2026-11-12 22:15:58', '2026-11-12 22:41:12'),
(5, 12, 'Koridor 03: Lingkar Wilis (Madiun - Ponorogo - Nganjuk - Kediri)', 'KOR-03', 135.2, 'koridor_geo_5.kml', 0, '2026-11-12 22:15:58', '2026-11-12 22:41:12'),
(6, 22, 'Koridor 04: Bromo-Semeru (Pasuruan - Probolinggo - Lumajang)', 'KOR-04', 112.7, 'koridor_geo_6.kml', 0, '2026-11-12 22:15:58', '2026-11-12 22:41:12'),
(7, 7, 'Koridor 05: Tapal Kuda Timur (Jember - Bondowoso - Situbondo)', 'KOR-05', 88.4, 'koridor_geo_7.kml', 0, '2026-11-12 22:15:58', '2026-11-12 22:43:57'),
(8, 1, 'Koridor 06: Trans Madura (Bangkalan - Sampang - Pamekasan - Sumenep)', 'KOR-06', 156.3, 'koridor_geo_8.kml', 0, '2026-11-12 22:15:58', '2026-11-12 22:41:12'),
(9, 38, 'Koridor 07: Poros Metropolitan (Mojokerto - Sidoarjo - Surabaya)', 'KOR-07', 64.1, 'koridor_geo_9.kml', 0, '2026-11-12 22:15:58', '2026-11-12 22:43:57');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_11_08_000001_add_username_to_users_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `provinsi`
--

CREATE TABLE `provinsi` (
  `id` int(11) NOT NULL,
  `nama_provinsi` varchar(100) NOT NULL,
  `void` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `provinsi`
--

INSERT INTO `provinsi` (`id`, `nama_provinsi`, `void`, `created_at`, `updated_at`) VALUES
(1, 'Jawa Timur', 0, '2026-11-12 13:20:01', '2026-11-12 13:20:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `nama_role` varchar(100) NOT NULL,
  `void` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `role`
--

INSERT INTO `role` (`id`, `nama_role`, `void`, `created_at`, `updated_at`) VALUES
(1, 'administrator', 0, '2026-11-12 14:38:45', '2026-11-12 14:38:45'),
(2, 'pu_turdal', 0, '2026-11-12 14:38:45', '2026-11-12 14:38:45'),
(3, 'bappeda', 0, '2026-11-12 14:38:45', '2026-11-12 14:38:45'),
(4, 'pu_bintek', 0, '2026-11-12 14:38:45', '2026-11-12 14:38:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ruas_jalan`
--

CREATE TABLE `ruas_jalan` (
  `id` int(11) NOT NULL,
  `kabupaten_kota_id` int(11) NOT NULL,
  `koridor_id` int(11) DEFAULT NULL,
  `nama_ruas` varchar(255) NOT NULL,
  `nomor_ruas` varchar(100) DEFAULT NULL,
  `panjang_ruas` double DEFAULT NULL,
  `status_jalan` enum('Kabupaten/Kota','Non Status') NOT NULL DEFAULT 'Kabupaten/Kota',
  `file_kml_ruas` varchar(255) DEFAULT NULL,
  `void` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ruas_jalan`
--

INSERT INTO `ruas_jalan` (`id`, `kabupaten_kota_id`, `koridor_id`, `nama_ruas`, `nomor_ruas`, `panjang_ruas`, `status_jalan`, `file_kml_ruas`, `void`, `created_at`, `updated_at`) VALUES
(1, 17, 5, 'Ruas Jalan Kresikan - Dawung', 'RUAS-01', 22.4, 'Kabupaten/Kota', 'jalan_geo_1.kml', 0, '2026-11-12 17:51:17', '2026-11-12 22:43:58'),
(2, 28, 3, 'Ruas Jalan Tuban - Babat', 'RUAS-02', 31.8, 'Kabupaten/Kota', 'jalan_geo_2.kml', 0, '2026-11-12 17:55:19', '2026-11-12 22:43:58'),
(3, 3, NULL, 'tes', 'tes', 3.6, 'Kabupaten/Kota', '1794531957_1794471188-doc.kml', 1, '2026-11-12 18:05:57', '2026-11-13 05:24:22'),
(4, 29, 4, 'Ruas Jalan Blitar - Tulungagung', 'RUAS-04', 28.5, 'Kabupaten/Kota', 'jalan_geo_4.kml', 0, '2026-11-12 18:41:30', '2026-11-12 22:43:58'),
(5, 10, 3, 'Ruas Jalan Paciran - Brondong', 'RUAS-05', 14.2, 'Kabupaten/Kota', 'jalan_geo_5.kml', 0, '2026-11-12 22:15:58', '2026-11-12 22:43:58'),
(6, 10, 3, 'Ruas Jalan Babat - Sukodadi', 'RUAS-06', 18.6, 'Kabupaten/Kota', 'jalan_geo_6.kml', 0, '2026-11-12 22:15:58', '2026-11-12 22:43:58'),
(7, 6, 3, 'Ruas Jalan Manyar - Sidayu', 'RUAS-07', 17.9, 'Kabupaten/Kota', 'jalan_geo_7.kml', 0, '2026-11-12 22:15:58', '2026-11-12 22:43:58'),
(8, 20, 9, 'Ruas Jalan Porong - Bangil', 'RUAS-08', 16.3, 'Kabupaten/Kota', 'jalan_geo_8.kml', 0, '2026-11-12 22:15:58', '2026-11-12 22:43:58'),
(9, 20, 9, 'Ruas Jalan Pandaan - Purwosari', 'RUAS-09', 12.5, 'Kabupaten/Kota', 'jalan_geo_9.kml', 0, '2026-11-12 22:15:58', '2026-11-12 22:43:58'),
(10, 14, 9, 'Ruas Jalan Singosari - Kepanjen', 'RUAS-10', 32.4, 'Kabupaten/Kota', 'jalan_geo_10.kml', 0, '2026-11-12 22:15:58', '2026-11-12 22:43:58'),
(11, 22, 6, 'Ruas Jalan Tongas - Banyuanyar', 'RUAS-11', 19.8, 'Kabupaten/Kota', 'jalan_geo_11.kml', 0, '2026-11-12 22:15:59', '2026-11-12 22:43:58'),
(12, 11, 6, 'Ruas Jalan Ranuyoso - Kedungjajang', 'RUAS-12', 15.6, 'Kabupaten/Kota', 'jalan_geo_12.kml', 0, '2026-11-12 22:15:59', '2026-11-12 22:43:58'),
(13, 7, 7, 'Ruas Jalan Tanggul - Rambipuji', 'RUAS-13', 20.4, 'Kabupaten/Kota', 'jalan_geo_13.kml', 0, '2026-11-12 22:15:59', '2026-11-12 22:43:58'),
(14, 2, 2, 'Ruas Jalan Genteng - Rogojampi', 'RUAS-14', 24.8, 'Kabupaten/Kota', 'jalan_geo_14.kml', 0, '2026-11-12 22:15:59', '2026-11-12 22:43:58'),
(15, 12, 5, 'Ruas Jalan Caruban - Saradan', 'RUAS-15', 14.5, 'Kabupaten/Kota', 'jalan_geo_15.kml', 0, '2026-11-12 22:15:59', '2026-11-12 22:43:58'),
(16, 16, 5, 'Ruas Jalan Baron - Kertosono', 'RUAS-16', 8.2, 'Kabupaten/Kota', 'jalan_geo_16.kml', 0, '2026-11-12 22:15:59', '2026-11-12 22:43:58'),
(17, 8, 9, 'Ruas Jalan Mojoagung - Peterongan', 'RUAS-17', 13.8, 'Kabupaten/Kota', 'jalan_geo_17.kml', 0, '2026-11-12 22:15:59', '2026-11-12 22:43:58'),
(18, 18, 4, 'Ruas Jalan Ngadirojo - Sudimoro', 'RUAS-18', 11.2, 'Kabupaten/Kota', 'jalan_geo_18.kml', 0, '2026-11-12 22:15:59', '2026-11-12 22:43:58'),
(19, 27, 4, 'Ruas Jalan Durenan - Panggul', 'RUAS-19', 38.6, 'Kabupaten/Kota', 'jalan_geo_19.kml', 0, '2026-11-12 22:15:59', '2026-11-12 22:43:58'),
(20, 1, 8, 'Ruas Jalan Tanah Merah - Kwanyar', 'RUAS-20', 12.3, 'Kabupaten/Kota', 'jalan_geo_20.kml', 0, '2026-11-12 22:15:59', '2026-11-12 22:43:58'),
(21, 23, 8, 'Ruas Jalan Camplong - Torjun', 'RUAS-21', 18.2, 'Kabupaten/Kota', 'jalan_geo_21.kml', 0, '2026-11-12 22:15:59', '2026-11-12 22:43:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4PopMVQcrPMTe50MtdxMw8E9qru2d54muvr7GkYP', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoid0tCdGRUZVl1YVluaG5GZFJXbHM5SmZFUjBjNmdkSEhuSUhnd3JVTyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM1OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvdXN1bGFuLWlucHJlcyI7czo1OiJyb3V0ZSI7czoxMzoidXN1bGFuLWlucHJlcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6ImUwYTQ0NGIzZTBkZDEwNTIwZGE1NGM5ZGNjYzJjODBkZTY1MWNkOWM1Yjk3YzM5YWM3N2YxODVjZjliYjJlODEiO30=', 1794544917),
('B7sSsEYQ9pOAnbIg5LidshwmUeqpGtbuKBOyH3NG', NULL, '127.0.0.1', 'curl/8.13.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRjNYSkRKejNpZHlkWWJJdldOQlcwVjdQNk81Z1B3UWtFU2RRbEVNViI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMjoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2dlb3NwYXNpYWwiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozMjoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2dlb3NwYXNpYWwiO3M6NToicm91dGUiO3M6MTY6Imdlb3NwYXNpYWwuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1794548712),
('eCSsLnh5tXU7pU6Dt57g0G4pHwBwv3kjoY4Rlh89', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiVGxEVHlYM3MwQTN0MjVrNndSYWNBejR5ZVlsd2FVVFVuWVNkWEI5eCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMyOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZ2Vvc3Bhc2lhbCI7czo1OiJyb3V0ZSI7czoxNjoiZ2Vvc3Bhc2lhbC5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6IjNjMzk3NjdjNGFmMTE1ZmVhMWVjMGJkMDEyNmZhZDc4YjY4OTZiNzRlMjFiOWU0NjlmN2NhYzEyOTI4OTIzZDAiO30=', 1794557113),
('lpnCzNkwE0Ivpv0hwKEndRlvl2gPwaXgZ0UckYU8', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoibDlqNm1DajZBamFhUkZsSG1EbzhDU0tjdzIxOVN4ZWZrV2pNcU9pUSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMyOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZ2Vvc3Bhc2lhbCI7czo1OiJyb3V0ZSI7czoxNjoiZ2Vvc3Bhc2lhbC5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6ImUwYTQ0NGIzZTBkZDEwNTIwZGE1NGM5ZGNjYzJjODBkZTY1MWNkOWM1Yjk3YzM5YWM3N2YxODVjZjliYjJlODEiO30=', 1794557616),
('rKT1UJ6p96HZ9GJfsgu8ZoUMC4yVNsXxexPNbCOn', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRVFDQU8yd3hsdGI1Q0RXUXhhMzluQXlLb0lkSWhOMUpWRTZGR2NxaCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2NDoiM2MzOTc2N2M0YWYxMTVmZWExZWMwYmQwMTI2ZmFkNzhiNjg5NmI3NGUyMWI5ZTQ2OWY3Y2FjMTI5Mjg5MjNkMCI7fQ==', 1794543300),
('Tj5tjiKgCY6UfZdKDwT14ThRAZt57C1aPQcy45RA', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoibUxwamZuelZZVW1CYTdqYWFXMTV3aDFLMm4zTWVXRmtDTjBZRUJUSyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI2OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcm9sZSI7czo1OiJyb3V0ZSI7czoxMDoicm9sZS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6ImUwYTQ0NGIzZTBkZDEwNTIwZGE1NGM5ZGNjYzJjODBkZTY1MWNkOWM1Yjk3YzM5YWM3N2YxODVjZjliYjJlODEiO30=', 1794543832),
('XII4necelZQnzbWymhbgxZHsn7q6ei8j4uzVf8I0', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiMjhvejBKQUxLc01NQUFuREJvMUllN3E2Qkt0S3dqbzlUOFVGRkFreiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI2OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcnVhcyI7czo1OiJyb3V0ZSI7czoxMDoicnVhcy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6IjNjMzk3NjdjNGFmMTE1ZmVhMWVjMGJkMDEyNmZhZDc4YjY4OTZiNzRlMjFiOWU0NjlmN2NhYzEyOTI4OTIzZDAiO30=', 1794544324);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tematik_kawasan`
--

CREATE TABLE `tematik_kawasan` (
  `id` int(11) NOT NULL,
  `nama_tematik` varchar(255) NOT NULL,
  `void` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tematik_kawasan`
--

INSERT INTO `tematik_kawasan` (`id`, `nama_tematik`, `void`, `created_at`, `updated_at`) VALUES
(1, 'Swasembada Pangan - Pertanian', 0, '2026-11-12 14:49:13', '2026-11-12 19:45:28'),
(2, 'Swasembada Pangan - Perkebunan', 0, '2026-11-12 14:49:13', '2026-11-12 14:49:13'),
(3, 'Swasembada Pangan - Perikanan', 0, '2026-11-12 14:49:13', '2026-11-12 14:49:13'),
(4, 'Swasembada Pangan - Peternakan', 0, '2026-11-12 14:49:13', '2026-11-12 14:49:13'),
(5, 'Swasembada Pangan - Kawasan Mendukung Program MBG', 0, '2026-11-12 14:49:13', '2026-11-12 14:49:13'),
(6, 'Distribusi Energi - Perkebunan yang mendukung Energi Terbarukan', 0, '2026-11-12 14:49:13', '2026-11-12 14:49:13'),
(7, 'Distribusi Energi - Mendukung Distribusi BBM satu harga', 0, '2026-11-12 14:49:13', '2026-11-12 14:49:13'),
(8, 'Kawasan Produktif Lainnya - Kawasan Transmigrasi', 0, '2026-11-12 14:49:13', '2026-11-12 14:49:13'),
(9, 'Kawasan Produktif Lainnya - Kawasan Industri Prioritas', 0, '2026-11-12 14:49:13', '2026-11-12 14:49:13'),
(10, 'Kawasan Produktif Lainnya - Kawasan Strategis Daerah', 0, '2026-11-12 14:49:13', '2026-11-12 14:49:13'),
(11, 'Konektivitas - Terhubung Ke Status Lebih Tinggi (Jalan Nasional)', 0, '2026-11-12 14:49:13', '2026-11-12 14:49:13'),
(12, 'Konektivitas - Terhubung Ke Status Lebih Tinggi (Jalan Provinsi)', 0, '2026-11-12 14:49:13', '2026-11-12 14:49:13'),
(13, 'Konektivitas - Terhubung Ke Simpul Transportasi', 0, '2026-11-12 14:49:13', '2026-11-12 14:49:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `upt_dinas`
--

CREATE TABLE `upt_dinas` (
  `id` int(11) NOT NULL,
  `provinsi_id` int(11) NOT NULL,
  `nama_upt` varchar(150) NOT NULL,
  `void` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `upt_dinas`
--

INSERT INTO `upt_dinas` (`id`, `provinsi_id`, `nama_upt`, `void`, `created_at`, `updated_at`) VALUES
(1, 1, 'UPT PJJ Surabaya', 0, '2026-11-12 15:24:10', '2026-11-12 15:24:10'),
(2, 1, 'UPT PJJ Mojokerto', 0, '2026-11-12 15:24:10', '2026-11-12 15:24:10'),
(3, 1, 'UPT PJJ Malang', 0, '2026-11-12 15:24:10', '2026-11-12 15:24:10'),
(4, 1, 'UPT PJJ Kediri', 0, '2026-11-12 15:24:10', '2026-11-12 15:24:10'),
(5, 1, 'UPT PJJ Madiun', 0, '2026-11-12 15:24:10', '2026-11-12 15:24:10'),
(6, 1, 'UPT PJJ Pacitan', 0, '2026-11-12 15:24:10', '2026-11-12 15:24:10'),
(7, 1, 'UPT PJJ Bojonegoro', 0, '2026-11-12 15:24:10', '2026-11-12 15:24:10'),
(8, 1, 'UPT PJJ Probolinggo', 0, '2026-11-12 15:24:10', '2026-11-12 15:24:10'),
(9, 1, 'UPT PJJ Jember', 0, '2026-11-12 15:24:10', '2026-11-12 15:24:10'),
(10, 1, 'UPT PJJ Banyuwangi', 0, '2026-11-12 15:24:10', '2026-11-12 15:24:10'),
(11, 1, 'UPT PJJ Pamekasan', 0, '2026-11-12 15:24:10', '2026-11-12 15:24:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `void` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `role_id`, `void`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator E-Jalin', 'admin', 'admin@ejalin.jatimprov.go.id', 1, 0, NULL, '$2y$12$lVVeA6JZmE5sKzTqdLnkMOISL/GtidEh4PdoyRmcU7YXPqpzmalf.', NULL, '2026-11-11 18:42:52', '2026-11-11 18:42:52');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jembatan`
--
ALTER TABLE `jembatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jembatan_ruas_jalan` (`ruas_jalan_id`);

--
-- Indeks untuk tabel `jenis_penanganan`
--
ALTER TABLE `jenis_penanganan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kabupaten_kota`
--
ALTER TABLE `kabupaten_kota`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kabupaten_kota_provinsi` (`provinsi_id`),
  ADD KEY `fk_kabupaten_kota_upt` (`upt_id`);

--
-- Indeks untuk tabel `koridor`
--
ALTER TABLE `koridor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_koridor_kabupaten_kota` (`kabupaten_kota_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `provinsi`
--
ALTER TABLE `provinsi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ruas_jalan`
--
ALTER TABLE `ruas_jalan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ruas_kabupaten_kota` (`kabupaten_kota_id`),
  ADD KEY `fk_ruas_koridor` (`koridor_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `tematik_kawasan`
--
ALTER TABLE `tematik_kawasan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `upt_dinas`
--
ALTER TABLE `upt_dinas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_upt_dinas_provinsi` (`provinsi_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `fk_users_role` (`role_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jembatan`
--
ALTER TABLE `jembatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `jenis_penanganan`
--
ALTER TABLE `jenis_penanganan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kabupaten_kota`
--
ALTER TABLE `kabupaten_kota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT untuk tabel `koridor`
--
ALTER TABLE `koridor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `provinsi`
--
ALTER TABLE `provinsi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `ruas_jalan`
--
ALTER TABLE `ruas_jalan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `tematik_kawasan`
--
ALTER TABLE `tematik_kawasan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `upt_dinas`
--
ALTER TABLE `upt_dinas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `jembatan`
--
ALTER TABLE `jembatan`
  ADD CONSTRAINT `fk_jembatan_ruas_jalan` FOREIGN KEY (`ruas_jalan_id`) REFERENCES `ruas_jalan` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kabupaten_kota`
--
ALTER TABLE `kabupaten_kota`
  ADD CONSTRAINT `fk_kabupaten_kota_provinsi` FOREIGN KEY (`provinsi_id`) REFERENCES `provinsi` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kabupaten_kota_upt` FOREIGN KEY (`upt_id`) REFERENCES `upt_dinas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `koridor`
--
ALTER TABLE `koridor`
  ADD CONSTRAINT `fk_koridor_kabupaten_kota` FOREIGN KEY (`kabupaten_kota_id`) REFERENCES `kabupaten_kota` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `ruas_jalan`
--
ALTER TABLE `ruas_jalan`
  ADD CONSTRAINT `fk_ruas_kabupaten_kota` FOREIGN KEY (`kabupaten_kota_id`) REFERENCES `kabupaten_kota` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ruas_koridor` FOREIGN KEY (`koridor_id`) REFERENCES `koridor` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `upt_dinas`
--
ALTER TABLE `upt_dinas`
  ADD CONSTRAINT `fk_upt_dinas_provinsi` FOREIGN KEY (`provinsi_id`) REFERENCES `provinsi` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
