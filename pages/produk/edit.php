<?php
$page_title = 'Edit Produk';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = escape($_POST['nama_produk']);
    $kategori = escape($_POST['kategori']);
    $harga = escape($_POST['harga']);
    $stok = escape($_POST['stok']);
    $deskripsi = escape($_POST['deskripsi']);

    $query = "UPDATE produk SET 
              nama_produk = '$nama_produk',
              kategori = '$kategori',
              harga = '$harga',
              stok = '$stok',
              deskripsi = '$deskripsi'
              WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = 'Produk berhasil diupdate!';
        header('Location: index.php');
        exit();
    } else {
        $error = 'Gagal mengupdate produk: ' . mysqli_error($conn);
    }
}

$query = "SELECT * FROM produk WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$produk = mysqli_fetch_assoc($result);
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Produk</h1>
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
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Produk</h6>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kode Produk</label>
                            <input type="text" class="form-control"
                                value="<?= $produk['kode_produk'] ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Produk</label>
                            <input type="text" class="form-control" name="nama_produk"
                                value="<?= $produk['nama_produk'] ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kategori</label>
                            <select class="form-control" name="kategori" required>
                                <option value="Makanan" <?= $produk['kategori'] == 'Makanan' ? 'selected' : '' ?>>Makanan</option>
                                <option value="Minuman" <?= $produk['kategori'] == 'Minuman' ? 'selected' : '' ?>>Minuman</option>
                                <option value="Snack" <?= $produk['kategori'] == 'Snack' ? 'selected' : '' ?>>Snack</option>
                                <option value="ATK" <?= $produk['kategori'] == 'ATK' ? 'selected' : '' ?>>ATK</option>
                                <option value="Elektronik" <?= $produk['kategori'] == 'Elektronik' ? 'selected' : '' ?>>Elektronik</option>
                                <option value="Lainnya" <?= $produk['kategori'] == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="number" class="form-control" name="harga"
                                value="<?= $produk['harga'] ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Stok</label>
                            <input type="number" class="form-control" name="stok"
                                value="<?= $produk['stok'] ?>" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="3"><?= $produk['deskripsi'] ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </form>
        </div>
    </div>

</div>

<?php require_once '../../includes/footer.php'; ?>