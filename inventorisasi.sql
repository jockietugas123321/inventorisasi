-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 07 Jul 2025 pada 12.48
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventorisasi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `kode_barang` varchar(30) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `detail_barang` text DEFAULT NULL,
  `lokasi_penyimpanan` varchar(100) DEFAULT NULL,
  `kode_kategori` varchar(20) DEFAULT NULL,
  `nama_kategori` varchar(100) DEFAULT NULL,
  `kondisi` enum('baik','rusak_ringan','rusak_berat') DEFAULT 'baik',
  `nilai_awal` decimal(18,2) NOT NULL,
  `tahun_perolehan` year(4) NOT NULL,
  `kode_pegawai` varchar(20) DEFAULT NULL,
  `nama_pegawai` varchar(100) DEFAULT NULL,
  `status` enum('aktif','mutasi','dihapus') DEFAULT 'aktif',
  `dokumentasi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`kode_barang`, `nama_barang`, `detail_barang`, `lokasi_penyimpanan`, `kode_kategori`, `nama_kategori`, `kondisi`, `nilai_awal`, `tahun_perolehan`, `kode_pegawai`, `nama_pegawai`, `status`, `dokumentasi`) VALUES
('BRG-2025-001', 'Laptop Lenovo Thinkpad', 'Laptop untuk staf administrasi', 'Ruang IT', 'KTG-2025-001', 'Elektronik', 'baik', 12000000.00, '2023', 'PGW-2025-002', 'Budi Santoso', 'aktif', 'uploads/laptop1.jpg'),
('BRG-2025-002', 'Meja Kerja Kayu', 'Meja kerja utama ruang kepala', 'Ruang Kepala', 'KTG-2025-002', 'Meubel', 'rusak_ringan', 2500000.00, '2022', 'PGW-2025-001', 'Andi Setiawan', 'aktif', 'uploads/meja1.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `kode_kategori` varchar(20) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`kode_kategori`, `nama_kategori`, `deskripsi`) VALUES
('KTG-2025-001', 'Elektronik', 'Peralatan elektronik kantor'),
('KTG-2025-002', 'Meubel', 'Perabotan dan meubel kantor');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_rekap_barang`
--

CREATE TABLE `laporan_rekap_barang` (
  `kode_laporan` varchar(30) NOT NULL,
  `tanggal_laporan` date NOT NULL,
  `total_barang` int(11) DEFAULT NULL,
  `total_nilai_awal` decimal(18,2) DEFAULT NULL,
  `total_nilai_buku` decimal(18,2) DEFAULT NULL,
  `total_baik` int(11) DEFAULT NULL,
  `total_rusak_ringan` int(11) DEFAULT NULL,
  `total_rusak_berat` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `laporan_rekap_barang`
--

INSERT INTO `laporan_rekap_barang` (`kode_laporan`, `tanggal_laporan`, `total_barang`, `total_nilai_awal`, `total_nilai_buku`, `total_baik`, `total_rusak_ringan`, `total_rusak_berat`, `keterangan`) VALUES
('LRB-2025-001', '2025-06-01', 2, 14500000.00, 9200000.00, 2, 0, 0, 'Rekap awal tahun 2025');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_verifikasi`
--

CREATE TABLE `laporan_verifikasi` (
  `id` int(11) NOT NULL,
  `kode_pegawai` varchar(20) DEFAULT NULL,
  `nama_pegawai` varchar(100) DEFAULT NULL,
  `tanggal_verifikasi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mutasi`
--

CREATE TABLE `mutasi` (
  `kode_mutasi` varchar(30) NOT NULL,
  `kode_barang` varchar(30) DEFAULT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `lokasi_asal` varchar(100) DEFAULT NULL,
  `lokasi_tujuan` varchar(100) DEFAULT NULL,
  `kode_pegawai_asal` varchar(20) DEFAULT NULL,
  `nama_pegawai_asal` varchar(100) DEFAULT NULL,
  `kode_pegawai_tujuan` varchar(20) DEFAULT NULL,
  `nama_pegawai_tujuan` varchar(100) DEFAULT NULL,
  `tgl_mutasi` date NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mutasi`
--

INSERT INTO `mutasi` (`kode_mutasi`, `kode_barang`, `nama_barang`, `lokasi_asal`, `lokasi_tujuan`, `kode_pegawai_asal`, `nama_pegawai_asal`, `kode_pegawai_tujuan`, `nama_pegawai_tujuan`, `tgl_mutasi`, `keterangan`) VALUES
('MTS-2025-001', 'BRG-2025-001', 'Laptop Lenovo Thinkpad', 'Ruang IT', 'Ruang Kepala', 'PGW-2025-002', 'Budi Santoso', 'PGW-2025-001', 'Andi Setiawan', '2024-06-01', 'Mutasi laptop ke ruang kepala');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pegawai`
--

CREATE TABLE `pegawai` (
  `kode_pegawai` varchar(20) NOT NULL,
  `nama_pegawai` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(30) NOT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `kontak` varchar(50) DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pegawai`
--

INSERT INTO `pegawai` (`kode_pegawai`, `nama_pegawai`, `username`, `password`, `role`, `jabatan`, `kontak`, `aktif`) VALUES
('PGW-2025-001', 'Andi Setiawan', 'admin', '0192023a7bbd73250516f069df18b500', 'admin', 'Petugas Aset', '08123456789', 1),
('PGW-2025-002', 'Budi Santoso', 'satker', '6cb75f652a9b52798eb6cf2201057c73', 'satker', 'Petugas Satker', '08129876543', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengadaan`
--

CREATE TABLE `pengadaan` (
  `kode_pengadaan` varchar(30) NOT NULL,
  `kode_barang` varchar(30) DEFAULT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `kode_kategori` varchar(20) DEFAULT NULL,
  `nama_kategori` varchar(100) DEFAULT NULL,
  `tgl_pengadaan` date NOT NULL,
  `supplier` varchar(100) DEFAULT NULL,
  `nilai` decimal(18,2) DEFAULT NULL,
  `dokumen` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengadaan`
--

INSERT INTO `pengadaan` (`kode_pengadaan`, `kode_barang`, `nama_barang`, `kode_kategori`, `nama_kategori`, `tgl_pengadaan`, `supplier`, `nilai`, `dokumen`, `keterangan`) VALUES
('PNG-2025-001', 'BRG-2025-001', 'Laptop Lenovo Thinkpad', 'KTG-2025-001', 'Elektronik', '2023-01-10', 'PT Komputer Jaya', 12000000.00, 'INV-001', 'Pengadaan laptop administrasi'),
('PNG-2025-002', 'BRG-2025-002', 'Meja Kerja Kayu', 'KTG-2025-002', 'Meubel', '2022-12-15', 'CV Mebelindo', 2500000.00, 'INV-002', 'Pengadaan meja kepala');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penyusutan`
--

CREATE TABLE `penyusutan` (
  `kode_penyusutan` varchar(30) NOT NULL,
  `kode_barang` varchar(30) DEFAULT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `tahun` year(4) NOT NULL,
  `nilai_awal` decimal(18,2) DEFAULT NULL,
  `akumulasi` decimal(18,2) DEFAULT NULL,
  `nilai_buku` decimal(18,2) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penyusutan`
--

INSERT INTO `penyusutan` (`kode_penyusutan`, `kode_barang`, `nama_barang`, `tahun`, `nilai_awal`, `akumulasi`, `nilai_buku`, `keterangan`) VALUES
('PSN-2025-001', 'BRG-2025-001', 'Laptop Lenovo Thinkpad', '2024', 12000000.00, 4800000.00, 7200000.00, 'Penyusutan tahun ke-2'),
('PSN-2025-002', 'BRG-2025-002', 'Meja Kerja Kayu', '2024', 2500000.00, 500000.00, 2000000.00, 'Penyusutan tahun ke-2');

-- --------------------------------------------------------

--
-- Struktur dari tabel `service`
--

CREATE TABLE `service` (
  `id_service` int(11) NOT NULL,
  `kode_barang` varchar(30) NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `tanggal_service` date NOT NULL,
  `biaya` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('proses','selesai') NOT NULL DEFAULT 'proses'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `stock_opname`
--

CREATE TABLE `stock_opname` (
  `kode_opname` varchar(30) NOT NULL,
  `kode_barang` varchar(30) DEFAULT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `jumlah_sistem` int(11) DEFAULT NULL,
  `jumlah_fisik` int(11) DEFAULT NULL,
  `selisih` int(11) DEFAULT NULL,
  `kondisi_fisik` enum('baik','rusak_ringan','rusak_berat') DEFAULT NULL,
  `kode_pegawai` varchar(20) DEFAULT NULL,
  `nama_pegawai` varchar(100) DEFAULT NULL,
  `tanggal_opname` date NOT NULL,
  `catatan` text DEFAULT NULL,
  `dokumentasi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `stock_opname`
--

INSERT INTO `stock_opname` (`kode_opname`, `kode_barang`, `nama_barang`, `jumlah_sistem`, `jumlah_fisik`, `selisih`, `kondisi_fisik`, `kode_pegawai`, `nama_pegawai`, `tanggal_opname`, `catatan`, `dokumentasi`) VALUES
('OPN-2025-001', 'BRG-2025-001', 'Laptop Lenovo Thinkpad', 1, 1, 0, 'baik', 'PGW-2025-002', 'Budi Santoso', '2025-06-01', 'Barang sesuai', 'uploads/laptop1_opname.jpg'),
('OPN-2025-002', 'BRG-2025-002', 'Meja Kerja Kayu', 1, 1, 0, 'baik', 'PGW-2025-001', 'Andi Setiawan', '2025-06-01', 'Barang sesuai', 'uploads/meja1_opname.jpg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`kode_barang`),
  ADD KEY `kode_kategori` (`kode_kategori`),
  ADD KEY `kode_pegawai` (`kode_pegawai`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`kode_kategori`);

--
-- Indeks untuk tabel `laporan_rekap_barang`
--
ALTER TABLE `laporan_rekap_barang`
  ADD PRIMARY KEY (`kode_laporan`);

--
-- Indeks untuk tabel `laporan_verifikasi`
--
ALTER TABLE `laporan_verifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kode_pegawai` (`kode_pegawai`);

--
-- Indeks untuk tabel `mutasi`
--
ALTER TABLE `mutasi`
  ADD PRIMARY KEY (`kode_mutasi`),
  ADD KEY `kode_barang` (`kode_barang`);

--
-- Indeks untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`kode_pegawai`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `pengadaan`
--
ALTER TABLE `pengadaan`
  ADD PRIMARY KEY (`kode_pengadaan`),
  ADD KEY `kode_barang` (`kode_barang`);

--
-- Indeks untuk tabel `penyusutan`
--
ALTER TABLE `penyusutan`
  ADD PRIMARY KEY (`kode_penyusutan`),
  ADD KEY `kode_barang` (`kode_barang`);

--
-- Indeks untuk tabel `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id_service`);

--
-- Indeks untuk tabel `stock_opname`
--
ALTER TABLE `stock_opname`
  ADD PRIMARY KEY (`kode_opname`),
  ADD KEY `kode_barang` (`kode_barang`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `laporan_verifikasi`
--
ALTER TABLE `laporan_verifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `service`
--
ALTER TABLE `service`
  MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`kode_kategori`) REFERENCES `kategori` (`kode_kategori`),
  ADD CONSTRAINT `barang_ibfk_2` FOREIGN KEY (`kode_pegawai`) REFERENCES `pegawai` (`kode_pegawai`);

--
-- Ketidakleluasaan untuk tabel `laporan_verifikasi`
--
ALTER TABLE `laporan_verifikasi`
  ADD CONSTRAINT `laporan_verifikasi_ibfk_1` FOREIGN KEY (`kode_pegawai`) REFERENCES `pegawai` (`kode_pegawai`);

--
-- Ketidakleluasaan untuk tabel `mutasi`
--
ALTER TABLE `mutasi`
  ADD CONSTRAINT `mutasi_ibfk_1` FOREIGN KEY (`kode_barang`) REFERENCES `barang` (`kode_barang`);

--
-- Ketidakleluasaan untuk tabel `pengadaan`
--
ALTER TABLE `pengadaan`
  ADD CONSTRAINT `pengadaan_ibfk_1` FOREIGN KEY (`kode_barang`) REFERENCES `barang` (`kode_barang`);

--
-- Ketidakleluasaan untuk tabel `penyusutan`
--
ALTER TABLE `penyusutan`
  ADD CONSTRAINT `penyusutan_ibfk_1` FOREIGN KEY (`kode_barang`) REFERENCES `barang` (`kode_barang`);

--
-- Ketidakleluasaan untuk tabel `stock_opname`
--
ALTER TABLE `stock_opname`
  ADD CONSTRAINT `stock_opname_ibfk_1` FOREIGN KEY (`kode_barang`) REFERENCES `barang` (`kode_barang`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
