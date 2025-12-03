<?php
$page_title = 'Tambah Pelanggan';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = escape($_POST['nama']);
    $telepon = escape($_POST['telepon']);
    $email = escape($_POST['email']);
    $alamat = escape($_POST['alamat']);

    // Validasi telepon tidak duplikat
    $check = mysqli_query($conn, "SELECT * FROM pelanggan WHERE telepon = '$telepon'");
    if (mysqli_num_rows($check) > 0) {
        $error = 'Nomor telepon sudah terdaftar!';
    } else {
        $query = "INSERT INTO pelanggan (nama, telepon, email, alamat) 
                  VALUES ('$nama', '$telepon', '$email', '$alamat')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = 'Pelanggan berhasil ditambahkan!';
            header('Location: index.php');
            exit();
        } else {
            $error = 'Gagal menambahkan pelanggan: ' . mysqli_error($conn);
        }
    }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Pelanggan</h1>
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
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Pelanggan</h6>
        </div>
        <div class="card-body">
            <form method="POST" id="formPelanggan">
                <div class="form-group">
                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nama"
                        placeholder="Masukkan nama lengkap" required>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="telepon"
                                placeholder="08xxxxxxxxxx" pattern="[0-9]{10,13}" required>
                            <small class="form-text text-muted">Format: 08xxxxxxxxxx (10-13 digit)</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email"
                                placeholder="email@example.com">
                            <small class="form-text text-muted">Email bersifat opsional</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea class="form-control" name="alamat" rows="3"
                        placeholder="Masukkan alamat lengkap"></textarea>
                </div>

                <hr>

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

<script>
    document.getElementById('formPelanggan').addEventListener('submit', function(e) {
        var telepon = document.querySelector('input[name="telepon"]').value;

        // Validasi nomor telepon
        if (!/^[0-9]{10,13}$/.test(telepon)) {
            e.preventDefault();
            alert('Nomor telepon harus berupa angka 10-13 digit!');
            return false;
        }
    });
</script>

<?php require_once '../../includes/footer.php'; ?>