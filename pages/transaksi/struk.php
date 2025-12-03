<?php
$page_title = 'Struk Pembayaran';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

$id = $_GET['id'] ?? $_SESSION['last_transaksi'] ?? 0;

$query = "SELECT t.*, p.nama as nama_pelanggan, p.telepon 
          FROM transaksi t 
          LEFT JOIN pelanggan p ON t.pelanggan_id = p.id 
          WHERE t.id = '$id'";
$transaksi = mysqli_fetch_assoc(mysqli_query($conn, $query));

if (!$transaksi) {
    $_SESSION['error'] = 'Transaksi tidak ditemukan!';
    header('Location: index.php');
    exit();
}

$detail_query = "SELECT dt.*, pr.nama_produk, pr.kode_produk 
                 FROM detail_transaksi dt
                 JOIN produk pr ON dt.produk_id = pr.id
                 WHERE dt.transaksi_id = '$id'";
$detail_result = mysqli_query($conn, $detail_query);
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Struk Pembayaran</h1>
        <div>
            <button onclick="window.print()" class="btn btn-primary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-print"></i>
                </span>
                <span class="text">Cetak Struk</span>
            </button>
            <a href="index.php" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Transaksi Baru</span>
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow" id="struk">
                <div class="card-body">
                    <!-- Header Struk -->
                    <div class="text-center mb-4">
                        <h2 class="font-weight-bold">TOKO KASIR UKK</h2>
                        <p class="mb-0">Jl. Contoh No. 123, Tasikmalaya</p>
                        <p class="mb-0">Telp: 0265-123456</p>
                        <hr>
                    </div>

                    <!-- Info Transaksi -->
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong>No. Transaksi:</strong><br>
                            <?= $transaksi['no_transaksi'] ?>
                        </div>
                        <div class="col-6 text-right">
                            <strong>Tanggal:</strong><br>
                            <?= date('d/m/Y H:i', strtotime($transaksi['tanggal'])) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <strong>Pelanggan:</strong><br>
                            <?= $transaksi['nama_pelanggan'] ?? 'Umum' ?>
                            <?= $transaksi['telepon'] ? '<br>' . $transaksi['telepon'] : '' ?>
                        </div>
                        <div class="col-6 text-right">
                            <strong>Kasir:</strong><br>
                            <?= $transaksi['kasir'] ?>
                        </div>
                    </div>

                    <hr>

                    <!-- Detail Item -->
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($detail = mysqli_fetch_assoc($detail_result)): ?>
                                <tr>
                                    <td><?= $detail['nama_produk'] ?></td>
                                    <td class="text-center"><?= $detail['jumlah'] ?></td>
                                    <td class="text-right"><?= formatRupiah($detail['harga']) ?></td>
                                    <td class="text-right"><?= formatRupiah($detail['subtotal']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    <hr>

                    <!-- Total -->
                    <div class="row">
                        <div class="col-6">
                            <h5>TOTAL</h5>
                        </div>
                        <div class="col-6 text-right">
                            <h5 class="font-weight-bold"><?= formatRupiah($transaksi['total_harga']) ?></h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">Jumlah Bayar</div>
                        <div class="col-6 text-right"><?= formatRupiah($transaksi['jumlah_bayar']) ?></div>
                    </div>

                    <div class="row">
                        <div class="col-6">Kembalian</div>
                        <div class="col-6 text-right"><?= formatRupiah($transaksi['kembalian']) ?></div>
                    </div>

                    <hr>

                    <div class="text-center mt-4">
                        <p class="mb-0"><strong>Terima Kasih Atas Kunjungan Anda!</strong></p>
                        <p class="mb-0 small">Barang yang sudah dibeli tidak dapat dikembalikan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #struk,
        #struk * {
            visibility: visible;
        }

        #struk {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .btn,
        .sidebar,
        .topbar,
        .footer {
            display: none !important;
        }
    }
</style>

<?php require_once '../../includes/footer.php'; ?>