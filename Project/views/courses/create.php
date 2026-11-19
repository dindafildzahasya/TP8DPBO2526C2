
<?php

// Mengatur judul halaman
$pageTitle = "Tambah Mata Kuliah";

// Memanggil header halaman
require __DIR__ . "/../layouts/header.php";

?>

<!-- Judul halaman -->
<h2 class="mb-3">Tambah Mata Kuliah</h2>

<!-- Kartu formulir -->
<div class="card shadow-sm">

    <!-- Header formulir -->
    <div class="card-header bg-primary text-white">

        Formulir Tambah Mata Kuliah

    </div>

    <div class="card-body">

        <?php if (!empty($lecturers)): ?>

            <!-- Form untuk menambahkan mata kuliah -->
            <form action="index.php?action=course_store" method="POST">

                <!-- Token keamanan CSRF -->
                <input type="hidden"
                       name="csrf_token"
                       value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

                <!-- Input nama mata kuliah -->
                <div class="mb-3">

                    <label class="form-label">Nama Mata Kuliah</label>

                    <input type="text"
                           name="course_name"
                           class="form-control"
                           placeholder="Contoh: Algoritma dan Pemrograman"
                           maxlength="100"
                           required>

                </div>

                <!-- Input kode mata kuliah -->
                <div class="mb-3">

                    <label class="form-label">Kode Mata Kuliah</label>

                    <input type="text"
                           name="course_code"
                           class="form-control"
                           placeholder="Contoh: IF101"
                           maxlength="20"
                           required>

                </div>

                <!-- Input jumlah SKS -->
                <div class="mb-3">

                    <label class="form-label">Jumlah SKS</label>

                    <input type="number"
                           name="sks"
                           class="form-control"
                           placeholder="Contoh: 3"
                           min="1"
                           max="255"
                           step="1"
                           required>

                </div>

                <!-- Pilihan dosen pengampu -->
                <div class="mb-3">

                    <label class="form-label">Dosen Pengampu</label>

                    <select name="lecturer_id" class="form-select" required>

                        <option value="">-- Pilih Dosen --</option>

                        <!-- Mengambil seluruh data dosen -->
                        <?php foreach ($lecturers as $lecturer): ?>

                            <option value="<?= (int) $lecturer['id']; ?>">

                                <?= htmlspecialchars($lecturer['name'], ENT_QUOTES, 'UTF-8'); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Tombol menyimpan data -->
                <button type="submit" class="btn btn-success">

                    Simpan

                </button>

                <!-- Tombol kembali -->
                <a href="index.php?action=course_index"
                   class="btn btn-secondary">

                    Batal

                </a>

            </form>

        <?php else: ?>

            <!-- Pemberitahuan jika belum ada data dosen -->
            <div class="alert alert-warning">

                Belum ada data dosen. Tambahkan data dosen terlebih dahulu sebelum membuat mata kuliah.

            </div>

            <!-- Tombol menuju halaman tambah dosen -->
            <a href="index.php?action=create" class="btn btn-primary">

                Tambah Dosen

            </a>

            <!-- Tombol kembali -->
            <a href="index.php?action=course_index" class="btn btn-secondary">

                Kembali

            </a>

        <?php endif; ?>

    </div>

</div>

<?php

// Memanggil footer halaman
require __DIR__ . "/../layouts/footer.php";

?>
