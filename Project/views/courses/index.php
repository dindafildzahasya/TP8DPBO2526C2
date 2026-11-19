
<?php

// Mengatur judul halaman
$pageTitle = "Daftar Mata Kuliah";

// Memanggil header halaman
require __DIR__ . "/../layouts/header.php";

?>

<!-- Bagian judul halaman dan tombol tambah -->
<div class="d-flex justify-content-between align-items-center mb-3">

    <h2>Daftar Mata Kuliah</h2>

    <!-- Tombol menuju formulir tambah mata kuliah -->
    <a href="index.php?action=course_create" class="btn btn-primary">

        + Tambah Mata Kuliah

    </a>

</div>

<!-- Kartu untuk menampung tabel mata kuliah -->
<div class="card shadow-sm">

    <div class="card-body">

        <!-- Membuat tabel responsif -->
        <div class="table-responsive">

            <table class="table table-bordered table-striped align-middle">

                <!-- Bagian judul kolom tabel -->
                <thead class="table-dark">

                    <tr>

                        <th>ID</th>
                        <th>Nama Mata Kuliah</th>
                        <th>Kode Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Dosen Pengampu</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <!-- Bagian isi tabel -->
                <tbody>

                    <?php if (!empty($courses)): ?>

                        <!-- Mengulang seluruh data mata kuliah -->
                        <?php foreach ($courses as $course): ?>

                            <tr>

                                <!-- Menampilkan ID mata kuliah -->
                                <td>
                                    <?= (int) $course['id']; ?>
                                </td>

                                <!-- Menampilkan nama mata kuliah -->
                                <td>
                                    <?= htmlspecialchars($course['course_name'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>

                                <!-- Menampilkan kode mata kuliah -->
                                <td>
                                    <?= htmlspecialchars($course['course_code'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>

                                <!-- Menampilkan jumlah SKS -->
                                <td>
                                    <?= (int) $course['sks']; ?>
                                </td>

                                <!-- Menampilkan nama dosen pengampu -->
                                <td>
                                    <?= htmlspecialchars($course['lecturer_name'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>

                                <!-- Bagian tombol aksi -->
                                <td>

                                    <!-- Tombol menuju halaman edit -->
                                    <a href="index.php?action=course_edit&id=<?= (int) $course['id']; ?>"
                                       class="btn btn-warning btn-sm">

                                        Edit

                                    </a>

                                    <!-- Form untuk menghapus mata kuliah -->
                                    <form action="index.php?action=course_delete"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Apakah kamu yakin ingin menghapus mata kuliah ini?');">

                                        <!-- Mengirim ID mata kuliah -->
                                        <input type="hidden"
                                               name="id"
                                               value="<?= (int) $course['id']; ?>">

                                        <!-- Mengirim token CSRF -->
                                        <input type="hidden"
                                               name="csrf_token"
                                               value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

                                        <!-- Tombol hapus -->
                                        <button type="submit" class="btn btn-danger btn-sm">

                                            Hapus

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <!-- Ditampilkan apabila belum ada mata kuliah -->
                        <tr>

                            <td colspan="6" class="text-center">

                                Belum ada data mata kuliah.

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php

// Memanggil footer halaman
require __DIR__ . "/../layouts/footer.php";

?>
