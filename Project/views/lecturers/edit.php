<?php
/** @var array $lecturer Data dosen dari LecturerController */

// Mengatur judul halaman
$pageTitle = "Edit Dosen";

// Memanggil header
require __DIR__ . "/../layouts/header.php";

?>

<!-- Judul halaman -->
<h2 class="mb-3">Edit Data Dosen</h2>

<!-- Kartu formulir -->
<div class="card shadow-sm">
    <div class="card-header bg-warning">
        Formulir Edit Dosen
    </div>

    <div class="card-body">
        <!-- Formulir pengubahan data -->
        <form action="index.php?action=update" method="POST">
            <!-- Menyimpan ID dosen -->
            <input type="hidden" name="id" value="<?= (int) $lecturer['id']; ?>">

            <!-- Token keamanan CSRF -->
            <input type="hidden" name="csrf_token"
                  value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

            <!-- Input nama -->
            <div class="mb-3">
                <label class="form-label">Nama Dosen</label>

                <input type="text" name="name" class="form-control" maxlength="100"
                      value="<?= htmlspecialchars($lecturer['name'], ENT_QUOTES, 'UTF-8'); ?>"
                      required>
            </div>

            <!-- Input NIDN -->
            <div class="mb-3">
                <label class="form-label">NIDN</label>
                <input type="text" name="nidn" class="form-control" maxlength="20"
                      value="<?= htmlspecialchars($lecturer['nidn'], ENT_QUOTES, 'UTF-8'); ?>"
                      required>
            </div>

            <!-- Input nomor telepon -->
            <div class="mb-3">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="phone" class="form-control" maxlength="20"
                      value="<?= htmlspecialchars($lecturer['phone'], ENT_QUOTES, 'UTF-8'); ?>"
                      required>
            </div>

            <!-- Input tanggal bergabung -->
            <div class="mb-3">
                <label class="form-label">Tanggal Bergabung</label>
                <input type="date" name="join_date" class="form-control"
                      value="<?= htmlspecialchars($lecturer['join_date'], ENT_QUOTES, 'UTF-8'); ?>"
                      required>
            </div>

            <!-- Tombol simpan perubahan -->
            <button type="submit" class="btn btn-success">
                Simpan Perubahan
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