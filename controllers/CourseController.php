
<?php

// Memanggil file Model Course
require_once __DIR__ . "/../models/Course.php";

// Memanggil file Model Lecturer
require_once __DIR__ . "/../models/Lecturer.php";

// Membuat class CourseController
class CourseController {

    // Menyimpan objek Model Course
    private $courseModel;

    // Menyimpan objek Model Lecturer
    private $lecturerModel;

    // Constructor menerima koneksi database
    public function __construct($conn) {

        // Membuat objek Model Course
        $this->courseModel = new Course($conn);

        // Membuat objek Model Lecturer
        $this->lecturerModel = new Lecturer($conn);
    }

    // Method untuk memeriksa token CSRF
    private function verifyCsrf() {

        // Memeriksa keberadaan dan kecocokan token
        if (
            !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {

            // Menghentikan proses jika token tidak valid
            http_response_code(403);

            exit("Permintaan tidak valid. Silakan muat ulang halaman.");
        }
    }

    // Method untuk mengarahkan halaman dengan pesan
    private function redirectWithMessage(
        $message,
        $type = "success",
        $url = "index.php?action=course_index"
    ) {

        // Menyimpan pesan ke session
        $_SESSION['flash'] = [
            "message" => $message,
            "type" => $type
        ];

        // Mengarahkan pengguna ke halaman tujuan
        header("Location: " . $url);

        exit;
    }

    // Method untuk mengambil data dari formulir
    private function getFormData() {

        // Mengambil dan membersihkan data input
        return [
            "course_name" => trim($_POST['course_name'] ?? ""),
            "course_code" => trim($_POST['course_code'] ?? ""),
            "sks" => trim($_POST['sks'] ?? ""),
            "lecturer_id" => trim($_POST['lecturer_id'] ?? "")
        ];
    }

    // Method untuk memvalidasi data mata kuliah
    private function validateData($data) {

        // Memeriksa apakah nama mata kuliah kosong
        if ($data['course_name'] === "") {
            return false;
        }

        // Memeriksa apakah kode mata kuliah kosong
        if ($data['course_code'] === "") {
            return false;
        }

        // Memeriksa panjang nama mata kuliah
        if (strlen($data['course_name']) > 100) {
            return false;
        }

        // Memeriksa panjang kode mata kuliah
        if (strlen($data['course_code']) > 20) {
            return false;
        }

        // Memeriksa apakah SKS berupa bilangan bulat
        $sks = filter_var(
            $data['sks'],
            FILTER_VALIDATE_INT
        );

        // Memeriksa rentang SKS sesuai tipe TINYINT UNSIGNED
        if ($sks === false || $sks < 1 || $sks > 255) {
            return false;
        }

        // Memeriksa apakah ID dosen berupa bilangan bulat positif
        $lecturerId = filter_var(
            $data['lecturer_id'],
            FILTER_VALIDATE_INT
        );

        if ($lecturerId === false || $lecturerId < 1) {
            return false;
        }

        // Memeriksa apakah dosen yang dipilih benar-benar tersedia
        $lecturer = $this->lecturerModel->getById($lecturerId);

        if (!$lecturer) {
            return false;
        }

        // Mengembalikan true jika seluruh data valid
        return true;
    }

    // =========================
    // READ
    // =========================

    // Method untuk menampilkan daftar mata kuliah
    public function index() {

        // Mengambil seluruh data mata kuliah dari Model
        $courses = $this->courseModel->getAll();

        // Memanggil View daftar mata kuliah
        require __DIR__ . "/../views/courses/index.php";
    }

    // =========================
    // FORM CREATE
    // =========================

    // Method untuk menampilkan formulir tambah mata kuliah
    public function create() {

        // Mengambil seluruh data dosen untuk pilihan dropdown
        $lecturers = $this->lecturerModel->getAll();

        // Memanggil View formulir tambah mata kuliah
        require __DIR__ . "/../views/courses/create.php";
    }

    // =========================
    // CREATE
    // =========================

    // Method untuk memproses penambahan mata kuliah
    public function store() {

        // Memastikan request menggunakan POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header("Location: index.php?action=course_index");

            exit;
        }

        // Memeriksa token keamanan
        $this->verifyCsrf();

        // Mengambil data dari formulir
        $data = $this->getFormData();

        // Memeriksa kelengkapan dan validitas data
        if (!$this->validateData($data)) {

            $this->redirectWithMessage(
                "Data mata kuliah tidak valid. Periksa kembali formulir.",
                "danger",
                "index.php?action=course_create"
            );
        }

        try {

            // Memanggil Model untuk menyimpan data
            $success = $this->courseModel->create($data);

            if ($success) {

                $this->redirectWithMessage(
                    "Data mata kuliah berhasil ditambahkan."
                );
            }

        } catch (mysqli_sql_exception $e) {

            // Menangani kemungkinan kode mata kuliah duplikat
            $this->redirectWithMessage(
                "Data gagal disimpan. Pastikan kode mata kuliah belum digunakan.",
                "danger",
                "index.php?action=course_create"
            );
        }

        // Menampilkan pesan jika penyimpanan gagal
        $this->redirectWithMessage(
            "Data mata kuliah gagal ditambahkan.",
            "danger",
            "index.php?action=course_create"
        );
    }

    // =========================
    // FORM EDIT
    // =========================

    // Method untuk menampilkan formulir edit mata kuliah
    public function edit() {

        // Mengambil ID dari URL
        $id = filter_input(
            INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );

        // Memeriksa validitas ID
        if (!$id || $id < 1) {

            $this->redirectWithMessage(
                "ID mata kuliah tidak valid.",
                "danger"
            );
        }

        // Mengambil data mata kuliah berdasarkan ID
        $course = $this->courseModel->getById($id);

        // Memeriksa apakah data ditemukan
        if (!$course) {

            $this->redirectWithMessage(
                "Data mata kuliah tidak ditemukan.",
                "danger"
            );
        }

        // Mengambil daftar dosen untuk pilihan dropdown
        $lecturers = $this->lecturerModel->getAll();

        // Memanggil View edit mata kuliah
        require __DIR__ . "/../views/courses/edit.php";
    }

    // =========================
    // UPDATE
    // =========================

    // Method untuk memproses perubahan data mata kuliah
    public function update() {

        // Memastikan request menggunakan POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header("Location: index.php?action=course_index");

            exit;
        }

        // Memeriksa token keamanan
        $this->verifyCsrf();

        // Mengambil ID dari formulir
        $id = filter_input(
            INPUT_POST,
            'id',
            FILTER_VALIDATE_INT
        );

        // Memeriksa validitas ID
        if (!$id || $id < 1) {

            $this->redirectWithMessage(
                "ID mata kuliah tidak valid.",
                "danger"
            );
        }

        // Memastikan mata kuliah masih tersedia
        $course = $this->courseModel->getById($id);

        if (!$course) {

            $this->redirectWithMessage(
                "Data mata kuliah tidak ditemukan.",
                "danger"
            );
        }

        // Mengambil data terbaru dari formulir
        $data = $this->getFormData();

        // Memvalidasi data
        if (!$this->validateData($data)) {

            $this->redirectWithMessage(
                "Data mata kuliah tidak valid. Periksa kembali formulir.",
                "danger",
                "index.php?action=course_edit&id=" . $id
            );
        }

        try {

            // Memanggil Model untuk memperbarui data
            $success = $this->courseModel->update($id, $data);

            if ($success) {

                $this->redirectWithMessage(
                    "Data mata kuliah berhasil diperbarui."
                );
            }

        } catch (mysqli_sql_exception $e) {

            // Menangani kemungkinan kode mata kuliah duplikat
            $this->redirectWithMessage(
                "Data gagal diperbarui. Pastikan kode mata kuliah belum digunakan.",
                "danger",
                "index.php?action=course_edit&id=" . $id
            );
        }

        // Menampilkan pesan jika perubahan gagal
        $this->redirectWithMessage(
            "Data mata kuliah gagal diperbarui.",
            "danger",
            "index.php?action=course_edit&id=" . $id
        );
    }

    // =========================
    // DELETE
    // =========================

    // Method untuk memproses penghapusan mata kuliah
    public function delete() {

        // Memastikan request menggunakan POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header("Location: index.php?action=course_index");

            exit;
        }

        // Memeriksa token keamanan
        $this->verifyCsrf();

        // Mengambil ID dari formulir
        $id = filter_input(
            INPUT_POST,
            'id',
            FILTER_VALIDATE_INT
        );

        // Memeriksa validitas ID
        if (!$id || $id < 1) {

            $this->redirectWithMessage(
                "ID mata kuliah tidak valid.",
                "danger"
            );
        }

        // Memanggil Model untuk menghapus data
        if ($this->courseModel->delete($id)) {

            $this->redirectWithMessage(
                "Data mata kuliah berhasil dihapus."
            );

        } else {

            $this->redirectWithMessage(
                "Data mata kuliah gagal dihapus atau tidak ditemukan.",
                "danger"
            );
        }
    }
}
