<?php

// Memulai session untuk menyimpan data sementara
session_start();

// Membuat token CSRF jika belum tersedia
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Memanggil file koneksi database
require_once __DIR__ . "/models/Database.php";
// Memanggil file Controller Lecturer
require_once __DIR__ . "/controllers/LecturerController.php";
// Memanggil Controller Course
require_once __DIR__ . "/controllers/CourseController.php";
// Memanggil Controller untuk pengelolaan jadwal mata kuliah
require_once __DIR__ . "/controllers/CourseScheduleController.php";

// Membuat objek Database
$database = new Database();
// Mengambil koneksi database
$conn = $database->getConnection();
// Membuat objek Controller Lecturer
$controller = new LecturerController($conn);
// Membuat objek CourseController
$courseController = new CourseController($conn);
// Membuat objek CourseScheduleController
$scheduleController = new CourseScheduleController($conn);
// Mengambil action dari URL
$action = $_GET['action'] ?? 'index';

// Mengatur navigasi berdasarkan action
switch ($action) {
    // Menampilkan daftar dosen
    case 'index': $controller->index();
        break;

    // Menampilkan formulir tambah dosen
    case 'create': $controller->create();
        break;

    // Memproses penambahan dosen
    case 'store': $controller->store();
        break;

    // Menampilkan formulir edit dosen
    case 'edit': $controller->edit();
        break;

    // Memproses perubahan dosen
    case 'update': $controller->update();
        break;

    // Memproses penghapusan dosen
    case 'delete': $controller->delete();
        break;

    // =========================
    // ROUTING COURSE
    // =========================

    // Menampilkan daftar mata kuliah
    case 'course_index': $courseController->index();
        break;

    // Menampilkan formulir tambah mata kuliah
    case 'course_create': $courseController->create();
        break;

    // Memproses penambahan mata kuliah
    case 'course_store': $courseController->store();
        break;

    // Menampilkan formulir edit mata kuliah
    case 'course_edit': $courseController->edit();
        break;

    // Memproses perubahan mata kuliah
    case 'course_update': $courseController->update();
        break;

    // Memproses penghapusan mata kuliah
    case 'course_delete': $courseController->delete();
        break;

    // =========================
    // ROUTING COURSE SCHEDULE
    // =========================

    // Menampilkan daftar jadwal
    case 'schedule_index': $scheduleController->index();
        break;

    // Menampilkan formulir tambah jadwal
    case 'schedule_create': $scheduleController->create();
        break;

    // Memproses penambahan jadwal
    case 'schedule_store': $scheduleController->store();
        break;

    // Menampilkan formulir edit jadwal
    case 'schedule_edit': $scheduleController->edit();
        break;

    // Memproses perubahan jadwal
    case 'schedule_update': $scheduleController->update();
        break;

    // Memproses penghapusan jadwal
    case 'schedule_delete': $scheduleController->delete();
        break;

    // Menangani action yang tidak tersedia
    default:
        http_response_code(404);
        echo "Halaman tidak ditemukan.";
        break;
}