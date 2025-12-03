<?php
$page_title = 'Tambah Produk';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_produk = escape($_POST['kode_produk']);
    $nama_produk = escape($_POST['nama_produk']);
    $kategori = escape($_POST['kategori']);
    $harga = escape($_POST['harga']);
    $stok = escape($_POST['stok']);
    $deskripsi = escape($_POST['deskripsi']);

    $query = "INSERT INTO produk (kode_produk, nama_produk, kategori, harga, stok, deskripsi) 
              VALUES ('$kode_produk', '$nama_produk', '$kategori', '$harga', '$stok', '$deskripsi')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = 'Produk berhasil ditambahkan!';
        header('Location: index.php');
        exit();
    } else {
        $error = 'Gagal menambahkan produk: ' . mysqli_error($conn);
    }
}

// Generate kode produk otomatis
$query = "SELECT kode_produk FROM produk ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$last_code = mysqli_fetch_assoc($result);

if ($last_code) {
    $last_number = (int) substr($last_code['kode_produk'], 3);
    $new_number = $last_number + 1;
} else {
    $new_number = 1;
}
$kode_produk_auto = 'PRD' . str_pad($new_number, 4, '0', STR_PAD_LEFT);
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Produk</h1>
        <a href="index.php" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Kembali</span>
        </a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Produk</h6>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kode Produk</label>
                            <input type="text" class="form-control" name="kode_produk"
                                value="<?= $kode_produk_auto ?>" readonly required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Produk</label>
                            <input type="text" class="form-control" name="nama_produk"
                                placeholder="Masukkan nama produk" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kategori</label>
                            <select class="form-control" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Makanan">Makanan</option>
                                <option value="Minuman">Minuman</option>
                                <option value="Snack">Snack</option>
                                <option value="ATK">ATK</option>
                                <option value="Elektronik">Elektronik</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="number" class="form-control" name="harga"
                                placeholder="0" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Stok</label>
                            <input type="number" class="form-control" name="stok"
                                placeholder="0" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="3"
                        placeholder="Deskripsi produk (opsional)"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </form>
        </div>
    </div>

</div>

<?php require_once '../../includes/footer.php'; ?>