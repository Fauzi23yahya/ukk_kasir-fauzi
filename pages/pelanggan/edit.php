<?php
$page_title = 'Edit Pelanggan';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = escape($_POST['nama']);
    $telepon = escape($_POST['telepon']);
    $email = escape($_POST['email']);
    $alamat = escape($_POST['alamat']);

    $query = "UPDATE pelanggan SET 
              nama = '$nama',
              telepon = '$telepon',
              email = '$email',
              alamat = '$alamat'
              WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = 'Pelanggan berhasil diupdate!';
        header('Location: index.php');
        exit();
    } else {
        $error = 'Gagal mengupdate pelanggan: ' . mysqli_error($conn);
    }
}

$query = "SELECT * FROM pelanggan WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$pelanggan = mysqli_fetch_assoc($result);

if (!$pelanggan) {
    $_SESSION['error'] = 'Pelanggan tidak ditemukan!';
    header('Location: index.php');
    exit();
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Pelanggan</h1>
        <a href="index.php" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Kembali</span>
        </a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error ?>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Pelanggan</h6>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama"
                        value="<?= htmlspecialchars($pelanggan['nama']) ?>" required>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Telepon</label>
                            <input type="text" class="form-control" name="telepon"
                                value="<?= htmlspecialchars($pelanggan['telepon']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email"
                                value="<?= htmlspecialchars($pelanggan['email']) ?>">
                            <small class="form-text text-muted">Email bersifat opsional</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea class="form-control" name="alamat" rows="3"><?= htmlspecialchars($pelanggan['alamat']) ?></textarea>
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