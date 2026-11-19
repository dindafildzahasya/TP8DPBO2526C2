
<?php

// Memberi tahu editor bahwa variabel ini berasal dari Controller
/** @var array $courses */

// Mengatur judul halaman
$pageTitle = "Tambah Jadwal Mata Kuliah";

// Memanggil header halaman
require __DIR__ . "/../layouts/header.php";

?>

<!-- Judul halaman -->
<h2 class="mb-3">Tambah Jadwal Mata Kuliah</h2>

<!-- Kartu formulir -->
<div class="card shadow-sm">

    <!-- Header formulir -->
    <div class="card-header bg-primary text-white">

        Formulir Tambah Jadwal

    </div>

    <div class="card-body">

        <?php if (!empty($courses)): ?>

            <!-- Form untuk menambahkan jadwal -->
            <form action="index.php?action=schedule_store" method="POST">

                <!-- Token keamanan CSRF -->
                <input type="hidden"
                       name="csrf_token"
                       value="<?= htmlspecialchars(
                           $_SESSION['csrf_token'],
                           ENT_QUOTES,
                           'UTF-8'
                       ); ?>">

                <!-- Pilihan mata kuliah -->
                <div class="mb-3">

                    <label class="form-label">Mata Kuliah</label>

                    <select name="course_id" class="form-select" required>

                        <option value="">-- Pilih Mata Kuliah --</option>

                        <!-- Mengambil seluruh data mata kuliah -->
                        <?php foreach ($courses as $course): ?>

                            <option value="<?= (int) $course['id']; ?>">

                                <?= htmlspecialchars(
                                    $course['course_name'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                                (<?= htmlspecialchars(
                                    $course['course_code'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>)

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Pilihan hari -->
                <div class="mb-3">

                    <label class="form-label">Hari Perkuliahan</label>

                    <select name="day_of_week" class="form-select" required>

                        <option value="">-- Pilih Hari --</option>

                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>

                    </select>

                </div>

                <!-- Input jam mulai -->
                <div class="mb-3">

                    <label class="form-label">Jam Mulai</label>

                    <input type="time"
                           name="start_time"
                           class="form-control"
                           step="60"
                           required>

                </div>

                <!-- Input jam selesai -->
                <div class="mb-3">

                    <label class="form-label">Jam Selesai</label>

                    <input type="time"
                           name="end_time"
                           class="form-control"
                           step="60"
                           required>

                </div>

                <!-- Input ruangan -->
                <div class="mb-3">

                    <label class="form-label">Ruangan</label>

                    <input type="text"
                           name="room"
                           class="form-control"
                           placeholder="Contoh: Lab Komputer 1"
                           maxlength="50"
                           required>

                </div>

                <!-- Tombol simpan -->
                <button type="submit" class="btn btn-success">

                    Simpan

                </button>

                <!-- Tombol batal -->
                <a href="index.php?action=schedule_index"
                   class="btn btn-secondary">

                    Batal

                </a>

            </form>

        <?php else: ?>

            <!-- Pemberitahuan jika belum ada mata kuliah -->
            <div class="alert alert-warning">

                Belum ada data mata kuliah.
                Tambahkan mata kuliah terlebih dahulu sebelum membuat jadwal.

            </div>

            <!-- Tombol menuju halaman tambah mata kuliah -->
            <a href="index.php?action=course_create" class="btn btn-primary">

                Tambah Mata Kuliah

            </a>

            <!-- Tombol kembali -->
            <a href="index.php?action=schedule_index"
               class="btn btn-secondary">

                Kembali

            </a>

        <?php endif; ?>

    </div>

</div>

<?php

// Memanggil footer halaman
require __DIR__ . "/../layouts/footer.php";

?>
