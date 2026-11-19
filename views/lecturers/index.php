<?php

// Mengatur judul halaman
$pageTitle = "Daftar Dosen";

// Memanggil header
require __DIR__ . "/../layouts/header.php";

?>

<!-- Judul halaman -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Daftar Dosen</h2>
    <!-- Tombol menuju formulir tambah dosen -->
    <a href="index.php?action=create" class="btn btn-primary">
        + Tambah Dosen
    </a>
</div>

<!-- Tabel data dosen -->
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">

                <!-- Header tabel -->
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>NIDN</th>
                        <th>Telepon</th>
                        <th>Tanggal Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <!-- Isi tabel -->
                <tbody>
                    <?php if (!empty($lecturers)): ?>
                        <?php foreach ($lecturers as $lecturer): ?>
                            <tr>

                                <!-- Menampilkan ID -->
                                <td>
                                    <?= htmlspecialchars($lecturer['id'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>

                                <!-- Menampilkan nama -->
                                <td>
                                    <?= htmlspecialchars($lecturer['name'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>

                                <!-- Menampilkan NIDN -->
                                <td>
                                    <?= htmlspecialchars($lecturer['nidn'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>

                                <!-- Menampilkan nomor telepon -->
                                <td>
                                    <?= htmlspecialchars($lecturer['phone'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>

                                <!-- Menampilkan tanggal bergabung -->
                                <td>
                                    <?= htmlspecialchars($lecturer['join_date'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>

                                <!-- Tombol aksi -->
                                <td>

                                    <!-- Tombol edit -->
                                    <a href="index.php?action=edit&id=<?= (int) $lecturer['id']; ?>"
                                      class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <!-- Form hapus -->
                                    <form action="index.php?action=delete"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Apakah kamu yakin ingin menghapus data dosen ini?');">

                                        <!-- ID dosen -->
                                        <input type="hidden" name="id" value="<?= (int) $lecturer['id']; ?>">

                                        <!-- Token keamanan -->
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

                                        <!-- Tombol hapus -->
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    <?php else: ?>

                        <!-- Ditampilkan jika belum ada data dosen -->
                        <tr>
                            <td colspan="6" class="text-center">
                                Belum ada data dosen.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php

// Memanggil footer
require __DIR__ . "/../layouts/footer.php";

?>