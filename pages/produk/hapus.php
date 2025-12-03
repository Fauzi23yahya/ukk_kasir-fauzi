<?php
require_once '../../config/koneksi.php';

$id = $_GET['id'];

$query = "DELETE FROM produk WHERE id = '$id'";

if (mysqli_query($conn, $query)) {
    $_SESSION['success'] = 'Produk berhasil dihapus!';
} else {
    $_SESSION['error'] = 'Gagal menghapus produk!';
}

header('Location: index.php');
exit();
