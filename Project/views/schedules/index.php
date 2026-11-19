
<?php

// Memberi tahu editor bahwa variabel ini berasal dari Controller
/** @var array $schedules */

// Mengatur judul halaman
$pageTitle = "Daftar Jadwal Mata Kuliah";

// Memanggil header halaman
require __DIR__ . "/../layouts/header.php";

?>

<!-- Bagian judul halaman dan tombol tambah -->
<div class="d-flex justify-content-between align-items-center mb-3">

    <h2>Daftar Jadwal Mata Kuliah</h2>

    <!-- Tombol menuju formulir tambah jadwal -->
    <a href="index.php?action=schedule_create" class="btn btn-primary">

        + Tambah Jadwal

    </a>

</div>

<!-- Kartu untuk menampung tabel jadwal -->
<div class="card shadow-sm">

    <div class="card-body">

        <!-- Membuat tabel responsif -->
        <div class="table-responsive">

            <table class="table table-bordered table-striped align-middle">

                <!-- Bagian judul kolom -->
                <thead class="table-dark">

                    <tr>

                        <th>ID</th>
                        <th>Mata Kuliah</th>
                        <th>Kode</th>
                        <th>Dosen Pengampu</th>
                        <th>Hari</th>
                        <th>Waktu</th>
                        <th>Ruangan</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <!-- Bagian isi tabel -->
                <tbody>

                    <?php if (!empty($schedules)): ?>

                        <!-- Mengulang seluruh data jadwal -->
                        <?php foreach ($schedules as $schedule): ?>

                            <tr>

                                <!-- Menampilkan ID jadwal -->
                                <td>
                                    <?= (int) $schedule['id']; ?>
                                </td>

                                <!-- Menampilkan nama mata kuliah -->
                                <td>
                                    <?= htmlspecialchars(
                                        $schedule['course_name'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>
                                </td>

                                <!-- Menampilkan kode mata kuliah -->
                                <td>
                                    <?= htmlspecialchars(
                                        $schedule['course_code'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>
                                </td>

                                <!-- Menampilkan nama dosen -->
                                <td>
                                    <?= htmlspecialchars(
                                        $schedule['lecturer_name'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>
                                </td>

                                <!-- Menampilkan hari perkuliahan -->
                                <td>
                                    <?= htmlspecialchars(
                                        $schedule['day_of_week'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>
                                </td>

                                <!-- Menampilkan jam mulai dan selesai -->
                                <td>
                                    <?= htmlspecialchars(
                                        substr($schedule['start_time'], 0, 5),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                    -

                                    <?= htmlspecialchars(
                                        substr($schedule['end_time'], 0, 5),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>
                                </td>

                                <!-- Menampilkan nama ruangan -->
                                <td>
                                    <?= htmlspecialchars(
                                        $schedule['room'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>
                                </td>

                                <!-- Bagian tombol aksi -->
                                <td>

                                    <!-- Tombol menuju halaman edit -->
                                    <a href="index.php?action=schedule_edit&id=<?= (int) $schedule['id']; ?>"
                                       class="btn btn-warning btn-sm">

                                        Edit

                                    </a>

                                    <!-- Form penghapusan jadwal -->
                                    <form action="index.php?action=schedule_delete"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Apakah kamu yakin ingin menghapus jadwal ini?');">

                                        <!-- Mengirim ID jadwal -->
                                        <input type="hidden"
                                               name="id"
                                               value="<?= (int) $schedule['id']; ?>">

                                        <!-- Mengirim token CSRF -->
                                        <input type="hidden"
                                               name="csrf_token"
                                               value="<?= htmlspecialchars(
                                                   $_SESSION['csrf_token'],
                                                   ENT_QUOTES,
                                                   'UTF-8'
                                               ); ?>">

                                        <!-- Tombol hapus -->
                                        <button type="submit" class="btn btn-danger btn-sm">

                                            Hapus

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <!-- Ditampilkan jika belum ada jadwal -->
                        <tr>

                            <td colspan="8" class="text-center">

                                Belum ada jadwal mata kuliah.

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
