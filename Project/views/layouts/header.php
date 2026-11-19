<?php

// Mengatur judul halaman jika belum ditentukan
$pageTitle = $pageTitle ?? "Manajemen Data Dosen";

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <!-- Mengatur karakter halaman -->
    <meta charset="UTF-8">
    <!-- Mengatur tampilan responsif -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Menghubungkan Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Judul halaman -->
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
</head>

<body>
    <!-- Navbar website -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <!-- Nama aplikasi -->
            <a class="navbar-brand" href="index.php">
                Manajemen Akademik
            </a>

            <!-- Menu navigasi -->
            <div class="navbar-nav">
                <!-- Menu data dosen -->
                <a class="nav-link" href="index.php">
                    Dosen
                </a>

                <!-- Menu data mata kuliah -->
                <a class="nav-link" href="index.php?action=course_index">
                    Mata Kuliah
                </a>

                <!-- Menu jadwal mata kuliah -->
                <a class="nav-link" href="index.php?action=schedule_index">
                    Jadwal
                </a>

            </div>
        </div>
    </nav>

    <!-- Container utama halaman -->
    <div class="container my-4">
        <!-- Menampilkan pesan hasil operasi CRUD -->
        <?php if (isset($_SESSION['flash'])): ?>

            <?php
            // Mengambil pesan dari session
            $flash = $_SESSION['flash'];
            // Menghapus pesan agar hanya muncul sekali
            unset($_SESSION['flash']);
            // Membatasi jenis alert yang diperbolehkan
            $allowedTypes = ['success', 'danger', 'warning', 'info'];

            $alertType = in_array($flash['type'], $allowedTypes, true) ? $flash['type'] : 'info';
            ?>

            <div class="alert alert-<?= $alertType; ?> alert-dismissible fade show">
                <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8'); ?>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>

        <?php endif; ?>