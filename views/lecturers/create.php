<?php

// Mengatur judul halaman
$pageTitle = "Tambah Dosen";

// Memanggil header
require __DIR__ . "/../layouts/header.php";

?>

<!-- Judul halaman -->
<h2 class="mb-3">Tambah Data Dosen</h2>

<!-- Kartu formulir -->
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        Formulir Tambah Dosen
    </div>

    <div class="card-body">
        <!-- Formulir penambahan data -->
        <form action="index.php?action=store" method="POST">

            <!-- Token keamanan CSRF -->
            <input type="hidden" name="csrf_token"
                  value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

            <!-- Input nama -->
            <div class="mb-3">
                <label class="form-label">Nama Dosen</label>
                <input type="text" name="name" class="form-control"
                      maxlength="100" required>
            </div>

            <!-- Input NIDN -->
            <div class="mb-3">
                <label class="form-label">NIDN</label>
                <input type="text" name="nidn" class="form-control"
                      maxlength="20" required>
            </div>

            <!-- Input nomor telepon -->
            <div class="mb-3">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="phone" class="form-control"
                      maxlength="20" required>
            </div>

            <!-- Input tanggal bergabung -->
            <div class="mb-3">
                <label class="form-label">Tanggal Bergabung</label>
                <input type="date" name="join_date" class="form-control" required>
            </div>

            <!-- Tombol simpan -->
            <button type="submit" class="btn btn-success">
                Simpan
            </button>

            <!-- Tombol kembali -->
            <a href="index.php" class="btn btn-secondary">
                Batal
            </a>
        </form>
    </div>
</div>

<?php

// Memanggil footer
require __DIR__ . "/../layouts/footer.php";

?>