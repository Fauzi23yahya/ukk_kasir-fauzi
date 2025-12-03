<?php
require_once '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

$pelanggan_id = $_POST['pelanggan_id'] ?: NULL;
$items = json_decode($_POST['items'], true);
$total_harga = (int) $_POST['total'];
$jumlah_bayar = (int) $_POST['jumlah_bayar'];
$kembalian = $jumlah_bayar - $total_harga;

// Validasi
if (empty($items) || $jumlah_bayar < $total_harga) {
    $_SESSION['error'] = 'Data transaksi tidak valid!';
    header('Location: index.php');
    exit();
}

// Generate nomor transaksi
$tanggal = date('Ymd');
$query = "SELECT COUNT(*) as total FROM transaksi WHERE DATE(tanggal) = CURDATE()";
$result = mysqli_fetch_assoc(mysqli_query($conn, $query));
$urutan = $result['total'] + 1;
$no_transaksi = 'TRX' . $tanggal . str_pad($urutan, 4, '0', STR_PAD_LEFT);

// Mulai transaksi database
mysqli_begin_transaction($conn);

try {
    // Insert transaksi
    $pelanggan_sql = $pelanggan_id ? "'$pelanggan_id'" : "NULL";
    $query = "INSERT INTO transaksi (no_transaksi, pelanggan_id, tanggal, total_harga, jumlah_bayar, kembalian, kasir) 
              VALUES ('$no_transaksi', $pelanggan_sql, NOW(), '$total_harga', '$jumlah_bayar', '$kembalian', '{$_SESSION['user_name']}')";
    mysqli_query($conn, $query);
    
    $transaksi_id = mysqli_insert_id($conn);
    
    // Insert detail transaksi & update stok
    foreach ($items as $item) {
        $produk_id = escape($item['id']);
        $jumlah = (int) $item['jumlah'];
        $harga = (int) $item['harga'];
        $subtotal = (int) $item['subtotal'];
        
        // Insert detail
        $query = "INSERT INTO detail_transaksi (transaksi_id, produk_id, jumlah, harga, subtotal) 
                  VALUES ('$transaksi_id', '$produk_id', '$jumlah', '$harga', '$subtotal')";
        mysqli_query($conn, $query);
        
        // Update stok
        $query = "UPDATE produk SET stok = stok - $jumlah WHERE id = '$produk_id'";
        mysqli_query($conn, $query);
    }
    
    // Commit transaksi
    mysqli_commit($conn);
    
    $_SESSION['success'] = 'Transaksi berhasil! No. Transaksi: ' . $no_transaksi;
    $_SESSION['last_transaksi'] = $transaksi_id;
    
    // Redirect ke struk
    header('Location: struk.php?id=' . $transaksi_id);
    exit();
    
} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['error'] = 'Transaksi gagal: ' . $e->getMessage();
    header('Location: index.php');
    exit();
}
?>