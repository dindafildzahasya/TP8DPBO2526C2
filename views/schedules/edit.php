
<?php

// Memberi tahu editor bahwa variabel berasal dari Controller
/** @var array $schedule */
/** @var array $courses */

// Mengatur judul halaman
$pageTitle = "Edit Jadwal Mata Kuliah";

// Memanggil header halaman
require __DIR__ . "/../layouts/header.php";

?>

<!-- Judul halaman -->
<h2 class="mb-3">Edit Jadwal Mata Kuliah</h2>

<!-- Kartu formulir -->
<div class="card shadow-sm">

    <!-- Header formulir -->
    <div class="card-header bg-warning">

        Formulir Edit Jadwal

    </div>

    <div class="card-body">

        <!-- Form untuk mengubah data jadwal -->
        <form action="index.php?action=schedule_update" method="POST">

            <!-- Menyimpan ID jadwal -->
            <input type="hidden"
                   name="id"
                   value="<?= (int) $schedule['id']; ?>">

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

                    <!-- Mengulang seluruh data mata kuliah -->
                    <?php foreach ($courses as $course): ?>

                        <option
                            value="<?= (int) $course['id']; ?>"
                            <?= (int) $course['id'] === (int) $schedule['course_id'] ? 'selected' : ''; ?>>

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

                    <option value="Senin"
                        <?= $schedule['day_of_week'] === 'Senin' ? 'selected' : ''; ?>>
                        Senin
                    </option>

                    <option value="Selasa"
                        <?= $schedule['day_of_week'] === 'Selasa' ? 'selected' : ''; ?>>
                        Selasa
                    </option>

                    <option value="Rabu"
                        <?= $schedule['day_of_week'] === 'Rabu' ? 'selected' : ''; ?>>
                        Rabu
                    </option>

                    <option value="Kamis"
                        <?= $schedule['day_of_week'] === 'Kamis' ? 'selected' : ''; ?>>
                        Kamis
                    </option>

                    <option value="Jumat"
                        <?= $schedule['day_of_week'] === 'Jumat' ? 'selected' : ''; ?>>
                        Jumat
                    </option>

                    <option value="Sabtu"
                        <?= $schedule['day_of_week'] === 'Sabtu' ? 'selected' : ''; ?>>
                        Sabtu
                    </option>

                </select>

            </div>

            <!-- Input jam mulai -->
            <div class="mb-3">

                <label class="form-label">Jam Mulai</label>

                <input type="time"
                       name="start_time"
                       class="form-control"
                       step="60"
                       value="<?= htmlspecialchars(
                           substr($schedule['start_time'], 0, 5),
                           ENT_QUOTES,
                           'UTF-8'
                       ); ?>"
                       required>

            </div>

            <!-- Input jam selesai -->
            <div class="mb-3">

                <label class="form-label">Jam Selesai</label>

                <input type="time"
                       name="end_time"
                       class="form-control"
                       step="60"
                       value="<?= htmlspecialchars(
                           substr($schedule['end_time'], 0, 5),
                           ENT_QUOTES,
                           'UTF-8'
                       ); ?>"
                       required>

            </div>

            <!-- Input ruangan -->
            <div class="mb-3">

                <label class="form-label">Ruangan</label>

                <input type="text"
                       name="room"
                       class="form-control"
                       maxlength="50"
                       value="<?= htmlspecialchars(
                           $schedule['room'],
                           ENT_QUOTES,
                           'UTF-8'
                       ); ?>"
                       required>

            </div>

            <!-- Tombol menyimpan perubahan -->
            <button type="submit" class="btn btn-success">

                Simpan Perubahan

            </button>

            <!-- Tombol membatalkan perubahan -->
            <a href="index.php?action=schedule_index"
               class="btn btn-secondary">

                Batal

            </a>

        </form>

    </div>

</div>

<?php

// Memanggil footer halaman
require __DIR__ . "/../layouts/footer.php";

?>
