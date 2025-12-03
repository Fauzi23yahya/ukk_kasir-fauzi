<?php
$page_title = 'Transaksi Penjualan';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

// Ambil data produk
$produk_query = "SELECT * FROM produk WHERE stok > 0 ORDER BY nama_produk ASC";
$produk_result = mysqli_query($conn, $produk_query);

// Ambil data pelanggan
$pelanggan_query = "SELECT * FROM pelanggan ORDER BY nama ASC";
$pelanggan_result = mysqli_query($conn, $pelanggan_query);
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Transaksi Penjualan</h1>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['success'] ?>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    <?php unset($_SESSION['success']);
    endif; ?>

    <div class="row">
        <!-- Form Transaksi -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Transaksi</h6>
                </div>
                <div class="card-body">
                    <form id="formTransaksi">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pilih Produk</label>
                                    <select class="form-control" id="produk_id">
                                        <option value="">-- Pilih Produk --</option>
                                        <?php while ($produk = mysqli_fetch_assoc($produk_result)): ?>
                                            <option value="<?= $produk['id'] ?>"
                                                data-nama="<?= $produk['nama_produk'] ?>"
                                                data-harga="<?= $produk['harga'] ?>"
                                                data-stok="<?= $produk['stok'] ?>">
                                                <?= $produk['nama_produk'] ?> - <?= formatRupiah($produk['harga']) ?> (Stok: <?= $produk['stok'] ?>)
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Jumlah</label>
                                    <input type="number" class="form-control" id="jumlah" value="1" min="1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-primary btn-block" onclick="tambahItem()">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <hr>

                    <!-- Tabel Keranjang -->
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tabelKeranjang">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="keranjangBody">
                                <tr id="emptyRow">
                                    <td colspan="5" class="text-center text-muted">
                                        <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                                        <p>Keranjang masih kosong</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ringkasan & Pembayaran -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold">Ringkasan Pembayaran</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="proses.php" id="formPembayaran">
                        <div class="form-group">
                            <label>Pelanggan</label>
                            <select class="form-control" name="pelanggan_id">
                                <option value="">Pelanggan Umum</option>
                                <?php while ($pelanggan = mysqli_fetch_assoc($pelanggan_result)): ?>
                                    <option value="<?= $pelanggan['id'] ?>">
                                        <?= $pelanggan['nama'] ?> - <?= $pelanggan['telepon'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Item:</span>
                            <strong id="totalItem">0</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total Harga:</span>
                            <h4 class="text-success" id="totalHarga">Rp 0</h4>
                        </div>

                        <div class="form-group">
                            <label>Jumlah Bayar</label>
                            <input type="number" class="form-control form-control-lg"
                                name="jumlah_bayar" id="jumlahBayar"
                                placeholder="0" required>
                        </div>

                        <div class="form-group">
                            <label>Kembalian</label>
                            <input type="text" class="form-control form-control-lg bg-light"
                                id="kembalian" readonly value="Rp 0">
                        </div>

                        <input type="hidden" name="items" id="itemsData">
                        <input type="hidden" name="total" id="totalData">

                        <button type="submit" class="btn btn-success btn-block btn-lg" id="btnBayar" disabled>
                            <i class="fas fa-check-circle"></i> Proses Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    let keranjang = [];
    let totalHarga = 0;

    function tambahItem() {
        const produkSelect = document.getElementById('produk_id');
        const jumlahInput = document.getElementById('jumlah');

        if (!produkSelect.value) {
            alert('Pilih produk terlebih dahulu!');
            return;
        }

        const selectedOption = produkSelect.options[produkSelect.selectedIndex];
        const produkId = selectedOption.value;
        const namaProduk = selectedOption.dataset.nama;
        const harga = parseInt(selectedOption.dataset.harga);
        const stok = parseInt(selectedOption.dataset.stok);
        const jumlah = parseInt(jumlahInput.value);

        if (jumlah <= 0) {
            alert('Jumlah harus lebih dari 0!');
            return;
        }

        if (jumlah > stok) {
            alert('Stok tidak mencukupi! Stok tersedia: ' + stok);
            return;
        }

        // Cek apakah produk sudah ada di keranjang
        const existing = keranjang.findIndex(item => item.id === produkId);

        if (existing !== -1) {
            const totalJumlah = keranjang[existing].jumlah + jumlah;
            if (totalJumlah > stok) {
                alert('Total jumlah melebihi stok! Stok tersedia: ' + stok);
                return;
            }
            keranjang[existing].jumlah = totalJumlah;
            keranjang[existing].subtotal = keranjang[existing].jumlah * harga;
        } else {
            keranjang.push({
                id: produkId,
                nama: namaProduk,
                harga: harga,
                jumlah: jumlah,
                subtotal: harga * jumlah
            });
        }

        updateKeranjang();
        produkSelect.value = '';
        jumlahInput.value = 1;
    }

    function hapusItem(index) {
        keranjang.splice(index, 1);
        updateKeranjang();
    }

    function updateKeranjang() {
        const tbody = document.getElementById('keranjangBody');
        const emptyRow = document.getElementById('emptyRow');

        if (keranjang.length === 0) {
            emptyRow.style.display = 'table-row';
            document.getElementById('btnBayar').disabled = true;
            totalHarga = 0;
        } else {
            emptyRow.style.display = 'none';
            document.getElementById('btnBayar').disabled = false;

            let html = '';
            totalHarga = 0;

            keranjang.forEach((item, index) => {
                totalHarga += item.subtotal;
                html += `
                <tr>
                    <td>${item.nama}</td>
                    <td>Rp ${item.harga.toLocaleString('id-ID')}</td>
                    <td>${item.jumlah}</td>
                    <td>Rp ${item.subtotal.toLocaleString('id-ID')}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="hapusItem(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            });

            tbody.innerHTML = html + emptyRow.outerHTML;
        }

        document.getElementById('totalItem').textContent = keranjang.length;
        document.getElementById('totalHarga').textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');
        document.getElementById('itemsData').value = JSON.stringify(keranjang);
        document.getElementById('totalData').value = totalHarga;

        hitungKembalian();
    }

    function hitungKembalian() {
        const jumlahBayar = parseInt(document.getElementById('jumlahBayar').value) || 0;
        const kembalian = jumlahBayar - totalHarga;

        if (kembalian < 0) {
            document.getElementById('kembalian').value = 'Uang kurang!';
            document.getElementById('kembalian').classList.add('text-danger');
            document.getElementById('btnBayar').disabled = true;
        } else {
            document.getElementById('kembalian').value = 'Rp ' + kembalian.toLocaleString('id-ID');
            document.getElementById('kembalian').classList.remove('text-danger');
            if (keranjang.length > 0) {
                document.getElementById('btnBayar').disabled = false;
            }
        }
    }

    document.getElementById('jumlahBayar').addEventListener('input', hitungKembalian);

    document.getElementById('formPembayaran').addEventListener('submit', function(e) {
        if (keranjang.length === 0) {
            e.preventDefault();
            alert('Keranjang masih kosong!');
            return false;
        }

        const jumlahBayar = parseInt(document.getElementById('jumlahBayar').value) || 0;
        if (jumlahBayar < totalHarga) {
            e.preventDefault();
            alert('Jumlah bayar kurang!');
            return false;
        }
    });
</script>

<?php require_once '../../includes/footer.php'; ?>