<?php
// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ukk_kasir_fauzi');

// Koneksi ke database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Fungsi untuk mencegah SQL Injection
function escape($data)
{
    global $conn;
    return mysqli_real_escape_string($conn, $data);
}

// Fungsi untuk format rupiah
function formatRupiah($angka)
{
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
