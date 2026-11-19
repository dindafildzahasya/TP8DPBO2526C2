<?php

// Memanggil file Model Lecturer
require_once __DIR__ . "/../models/Lecturer.php";

// Membuat class LecturerController
class LecturerController {
    // Menyimpan objek Model Lecturer
    private $lecturerModel;

    // Constructor menerima koneksi database
    public function __construct($conn) {
        // Membuat objek Model Lecturer
        $this->lecturerModel = new Lecturer($conn);
    }

    // Method untuk memeriksa token CSRF
    private function verifyCsrf() {
        // Memeriksa apakah token dikirim dan sesuai dengan session
        if (!isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {

            // Menghentikan proses jika token tidak valid
            http_response_code(403);
            exit("Permintaan tidak valid. Silakan muat ulang halaman.");
        }
    }

    // Method untuk mengarahkan halaman dengan pesan
    private function redirectWithMessage($message, $type = "success", $url = "index.php") {
        // Menyimpan pesan ke session
        $_SESSION['flash'] = ["message" => $message, "type" => $type];

        // Mengarahkan pengguna ke halaman tujuan
        header("Location: " . $url);
        exit;
    }

    // Method untuk memeriksa kelengkapan data formulir
    private function validateData($data) {
        // Memeriksa apakah ada kolom yang kosong
        foreach ($data as $value) {

            if (empty($value)) {
                return false;
            }
        }

        // Memeriksa format tanggal
        $date = DateTime::createFromFormat('Y-m-d', $data['join_date']);

        return $date && $date->format('Y-m-d') === $data['join_date'];
    }

    // Method untuk mengambil data dari formulir
    private function getFormData() {

        // Mengambil dan membersihkan data input
        return [
            "name" => trim($_POST['name'] ?? ""),
            "nidn" => trim($_POST['nidn'] ?? ""),
            "phone" => trim($_POST['phone'] ?? ""),
            "join_date" => trim($_POST['join_date'] ?? "")
        ];
    }

    // =========================
    // READ
    // =========================

    // Method untuk menampilkan daftar dosen
    public function index() {
        // Mengambil seluruh data dosen dari Model
        $lecturers = $this->lecturerModel->getAll();

        // Memanggil View daftar dosen
        require __DIR__ . "/../views/lecturers/index.php";
    }

    // =========================
    // FORM CREATE
    // =========================

    // Method untuk menampilkan formulir tambah dosen
    public function create() {
        // Memanggil View formulir tambah dosen
        require __DIR__ . "/../views/lecturers/create.php";
    }

    // =========================
    // CREATE
    // =========================

    // Method untuk memproses penambahan dosen
    public function store() {
        // Memastikan request menggunakan POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php");
            exit;
        }

        // Memeriksa token keamanan
        $this->verifyCsrf();
        // Mengambil data dari formulir
        $data = $this->getFormData();

        // Memeriksa kelengkapan dan format data
        if (!$this->validateData($data)) {
            $this->redirectWithMessage(
                "Semua kolom harus diisi dengan benar.",
                "danger",
                "index.php?action=create"
            );
        }

        // Memanggil Model untuk menyimpan data
        if ($this->lecturerModel->create($data)) {
            $this->redirectWithMessage("Data dosen berhasil ditambahkan.");
        } else {
            $this->redirectWithMessage("Data dosen gagal ditambahkan.", "danger");
        }
    }

    // =========================
    // FORM EDIT
    // =========================

    // Method untuk menampilkan formulir edit dosen
    public function edit() {
        // Mengambil ID dari URL
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        // Memeriksa ID
        if (!$id || $id < 1) {
            $this->redirectWithMessage("ID dosen tidak valid.", "danger");
        }
        // Mengambil data dosen berdasarkan ID
        $lecturer = $this->lecturerModel->getById($id);

        // Memeriksa apakah data ditemukan
        if (!$lecturer) {
            $this->redirectWithMessage("Data dosen tidak ditemukan.", "danger");
        }

        // Memanggil View edit dosen
        require __DIR__ . "/../views/lecturers/edit.php";
    }

    // =========================
    // UPDATE
    // =========================

    // Method untuk memproses perubahan data dosen
    public function update() {
        // Memastikan request menggunakan POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php");
            exit;
        }

        // Memeriksa token keamanan
        $this->verifyCsrf();
        // Mengambil ID dari formulir
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        // Memeriksa ID
        if (!$id || $id < 1) {
            $this->redirectWithMessage("ID dosen tidak valid.", "danger");
        }

        // Mengambil data dari formulir
        $data = $this->getFormData();

        // Memeriksa kelengkapan dan format data
        if (!$this->validateData($data)) {

            $this->redirectWithMessage(
                "Semua kolom harus diisi dengan benar.",
                "danger",
                "index.php?action=edit&id=" . $id
            );
        }

        // Memanggil Model untuk memperbarui data
        if ($this->lecturerModel->update($id, $data)) {
            $this->redirectWithMessage("Data dosen berhasil diperbarui.");
        } else {
            $this->redirectWithMessage(
                "Data dosen gagal diperbarui atau tidak mengalami perubahan.",
                "warning"
            );
        }
    }

    // =========================
    // DELETE
    // =========================
    // Method untuk memproses penghapusan dosen
    public function delete() {
        // Memastikan request menggunakan POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php");
            exit;
        }

        // Memeriksa token keamanan
        $this->verifyCsrf();

        // Mengambil ID dosen dari formulir
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        // Memeriksa validitas ID
        if (!$id || $id < 1) {
            $this->redirectWithMessage(
                "ID dosen tidak valid.",
                "danger"
            );
        }
        try {
            // Memanggil Model untuk menghapus data dosen
            if ($this->lecturerModel->delete($id)) {
                $this->redirectWithMessage("Data dosen berhasil dihapus.");
            } else {
                $this->redirectWithMessage(
                    "Data dosen gagal dihapus atau tidak ditemukan.",
                    "danger"
                );
            }
        } catch (mysqli_sql_exception $e) {
            // Menyimpan detail error ke log server
            error_log($e->getMessage());

            // Memeriksa apakah dosen masih digunakan oleh mata kuliah
            if ((int) $e->getCode() === 1451) {
                $this->redirectWithMessage(
                    "Dosen tidak dapat dihapus karena masih memiliki mata kuliah. Hapus mata kuliah terkait atau ganti dosen pengampunya terlebih dahulu.",
                    "warning"
                );
            }
            // Menampilkan pesan jika terjadi kesalahan database lainnya
            $this->redirectWithMessage(
                "Terjadi kesalahan saat menghapus data dosen.",
                "danger"
            );
        }
    }

}