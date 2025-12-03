<?php
$page_title = 'Laporan Penjualan';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

// Filter
$tanggal_dari = $_GET['dari'] ?? date('Y-m-d');
$tanggal_sampai = $_GET['sampai'] ?? date('Y-m-d');

// Query laporan
$query = "SELECT t.*, p.nama as nama_pelanggan 
          FROM transaksi t 
          LEFT JOIN pelanggan p ON t.pelanggan_id = p.id 
          WHERE DATE(t.tanggal) BETWEEN '$tanggal_dari' AND '$tanggal_sampai'
          ORDER BY t.tanggal DESC";
$result = mysqli_query($conn, $query);

// Hitung total
$total_query = "SELECT 
                COUNT(*) as total_transaksi,
                SUM(total_harga) as total_pendapatan
                FROM transaksi 
                WHERE DATE(tanggal) BETWEEN '$tanggal_dari' AND '$tanggal_sampai'";
$total = mysqli_fetch_assoc(mysqli_query($conn, $total_query));
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Penjualan</h1>
        <button onclick="cetakLaporan()" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-print"></i>
            </span>
            <span class="text">Cetak Laporan</span>
        </button>
    </div>

    <!-- Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
        </div>
        <div class="card-body">
            <form method="GET" class="form-inline">
                <label class="mr-2">Dari:</label>
                <input type="date" class="form-control mr-3" name="dari" value="<?= $tanggal_dari ?>">

                <label class="mr-2">Sampai:</label>
                <input type="date" class="form-control mr-3" name="sampai" value="<?= $tanggal_sampai ?>">

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="index.php" class="btn btn-secondary ml-2">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </form>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row">
        <div class="col-md-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Transaksi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $total['total_transaksi'] ?> transaksi
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pendapatan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= formatRupiah($total['total_pendapatan'] ?? 0) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Tabel Laporan -->
    <div class="card shadow mb-4" id="laporanCard">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Data Transaksi: <?= date('d/m/Y', strtotime($tanggal_dari)) ?> - <?= date('d/m/Y', strtotime($tanggal_sampai)) ?>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Transaksi</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Kasir</th>
                            <th>Total</th>
                            <th class="no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $grand_total = 0;
                        while ($row = mysqli_fetch_assoc($result)):
                            $grand_total += $row['total_harga'];
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $row['no_transaksi'] ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></td>
                                <td><?= $row['nama_pelanggan'] ?? 'Umum' ?></td>
                                <td><?= $row['kasir'] ?></td>
                                <td><?= formatRupiah($row['total_harga']) ?></td>
                                <td class="no-print">
                                    <a href="../transaksi/struk.php?id=<?= $row['id'] ?>"
                                        class="btn btn-info btn-sm" target="_blank">
                                        <i class="fas fa-receipt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold bg-light">
                            <td colspan="5" class="text-right">GRAND TOTAL:</td>
                            <td colspan="2"><?= formatRupiah($grand_total) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    function cetakLaporan() {
        window.print();
    }
</script>

<style>
    @media print {

        .no-print,
        .sidebar,
        .topbar,
        .footer,
        .btn {
            display: none !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        #laporanCard {
            page-break-inside: avoid;
        }
    }
</style>

<?php require_once '../../includes/footer.php'; ?>