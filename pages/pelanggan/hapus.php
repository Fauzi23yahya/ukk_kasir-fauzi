<?php
require_once '../../config/koneksi.php';

// Cek apakah ada ID
if (!isset($_GET['id'])) {
    $_SESSION['error'] = 'ID pelanggan tidak ditemukan!';
    header('Location: index.php');
    exit();
}

$id = escape($_GET['id']);

// Cek apakah pelanggan ada
$check = mysqli_query($conn, "SELECT * FROM pelanggan WHERE id = '$id'");
if (mysqli_num_rows($check) == 0) {
    $_SESSION['error'] = 'Pelanggan tidak ditemukan!';
    header('Location: index.php');
    exit();
}

// Cek apakah pelanggan memiliki transaksi
$check_transaksi = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE pelanggan_id = '$id'");
$transaksi = mysqli_fetch_assoc($check_transaksi);

if ($transaksi['total'] > 0) {
    $_SESSION['error'] = 'Tidak dapat menghapus pelanggan karena memiliki riwayat transaksi!';
    header('Location: index.php');
    exit();
}

// Hapus pelanggan
$query = "DELETE FROM pelanggan WHERE id = '$id'";

if (mysqli_query($conn, $query)) {
    $_SESSION['success'] = 'Pelanggan berhasil dihapus!';
} else {
    $_SESSION['error'] = 'Gagal menghapus pelanggan: ' . mysqli_error($conn);
}

header('Location: index.php');
exit();
?>