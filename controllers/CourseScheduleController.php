
<?php

// Memanggil Model CourseSchedule
require_once __DIR__ . "/../models/CourseSchedule.php";

// Memanggil Model Course
require_once __DIR__ . "/../models/Course.php";

// Membuat class CourseScheduleController
class CourseScheduleController {

    // Menyimpan objek Model CourseSchedule
    private $scheduleModel;

    // Menyimpan objek Model Course
    private $courseModel;

    // Constructor menerima koneksi database
    public function __construct($conn) {

        // Membuat objek Model CourseSchedule
        $this->scheduleModel = new CourseSchedule($conn);

        // Membuat objek Model Course
        $this->courseModel = new Course($conn);
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
        $url = "index.php?action=schedule_index"
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
            "course_id" => trim($_POST['course_id'] ?? ""),
            "day_of_week" => trim($_POST['day_of_week'] ?? ""),
            "start_time" => trim($_POST['start_time'] ?? ""),
            "end_time" => trim($_POST['end_time'] ?? ""),
            "room" => trim($_POST['room'] ?? "")
        ];
    }

    // Method untuk memeriksa format waktu
    private function isValidTime($time) {

        // Membuat objek waktu berdasarkan format jam dan menit
        $date = DateTime::createFromFormat('!H:i', $time);

        // Memastikan waktu sesuai format HH:MM
        return $date && $date->format('H:i') === $time;
    }

    // Method untuk memvalidasi data jadwal
    private function validateData($data) {

        // Memeriksa apakah ID mata kuliah berupa integer
        $courseId = filter_var(
            $data['course_id'],
            FILTER_VALIDATE_INT
        );

        // Memeriksa validitas ID mata kuliah
        if ($courseId === false || $courseId < 1) {
            return false;
        }

        // Memeriksa apakah mata kuliah tersedia
        $course = $this->courseModel->getById($courseId);

        if (!$course) {
            return false;
        }

        // Daftar hari yang diperbolehkan
        $allowedDays = [
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu'
        ];

        // Memeriksa hari perkuliahan
        if (!in_array($data['day_of_week'], $allowedDays, true)) {
            return false;
        }

        // Memeriksa format jam mulai
        if (!$this->isValidTime($data['start_time'])) {
            return false;
        }

        // Memeriksa format jam selesai
        if (!$this->isValidTime($data['end_time'])) {
            return false;
        }

        // Memastikan jam selesai lebih besar dari jam mulai
        if ($data['end_time'] <= $data['start_time']) {
            return false;
        }

        // Memeriksa nama ruangan
        if (
            $data['room'] === "" ||
            strlen($data['room']) > 50
        ) {
            return false;
        }

        // Mengembalikan true jika seluruh data valid
        return true;
    }

    // =========================
    // READ
    // =========================

    // Method untuk menampilkan seluruh jadwal
    public function index() {

        // Mengambil seluruh data jadwal dari Model
        $schedules = $this->scheduleModel->getAll();

        // Memanggil View daftar jadwal
        require __DIR__ . "/../views/schedules/index.php";
    }

    // =========================
    // FORM CREATE
    // =========================

    // Method untuk menampilkan formulir tambah jadwal
    public function create() {

        // Mengambil seluruh mata kuliah untuk dropdown
        $courses = $this->courseModel->getAll();

        // Memanggil View formulir tambah jadwal
        require __DIR__ . "/../views/schedules/create.php";
    }

    // =========================
    // CREATE
    // =========================

    // Method untuk memproses penambahan jadwal
    public function store() {

        // Memastikan request menggunakan POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header("Location: index.php?action=schedule_index");

            exit;
        }

        // Memeriksa token keamanan
        $this->verifyCsrf();

        // Mengambil data dari formulir
        $data = $this->getFormData();

        // Memeriksa kelengkapan dan validitas data
        if (!$this->validateData($data)) {

            $this->redirectWithMessage(
                "Data jadwal tidak valid. Periksa kembali formulir.",
                "danger",
                "index.php?action=schedule_create"
            );
        }

        try {

            // Memanggil Model untuk menyimpan jadwal
            $success = $this->scheduleModel->create($data);

            if ($success) {

                $this->redirectWithMessage(
                    "Jadwal mata kuliah berhasil ditambahkan."
                );
            }

        } catch (mysqli_sql_exception $e) {

            // Menangani kegagalan penyimpanan
            $this->redirectWithMessage(
                "Jadwal mata kuliah gagal ditambahkan.",
                "danger",
                "index.php?action=schedule_create"
            );
        }

        // Menampilkan pesan jika penyimpanan gagal
        $this->redirectWithMessage(
            "Jadwal mata kuliah gagal ditambahkan.",
            "danger",
            "index.php?action=schedule_create"
        );
    }

    // =========================
    // FORM EDIT
    // =========================

    // Method untuk menampilkan formulir edit jadwal
    public function edit() {

        // Mengambil ID jadwal dari URL
        $id = filter_input(
            INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );

        // Memeriksa validitas ID
        if (!$id || $id < 1) {

            $this->redirectWithMessage(
                "ID jadwal tidak valid.",
                "danger"
            );
        }

        // Mengambil data jadwal berdasarkan ID
        $schedule = $this->scheduleModel->getById($id);

        // Memeriksa apakah jadwal ditemukan
        if (!$schedule) {

            $this->redirectWithMessage(
                "Data jadwal tidak ditemukan.",
                "danger"
            );
        }

        // Mengambil daftar mata kuliah untuk dropdown
        $courses = $this->courseModel->getAll();

        // Memanggil View edit jadwal
        require __DIR__ . "/../views/schedules/edit.php";
    }

    // =========================
    // UPDATE
    // =========================

    // Method untuk memproses perubahan jadwal
    public function update() {

        // Memastikan request menggunakan POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header("Location: index.php?action=schedule_index");

            exit;
        }

        // Memeriksa token keamanan
        $this->verifyCsrf();

        // Mengambil ID jadwal dari formulir
        $id = filter_input(
            INPUT_POST,
            'id',
            FILTER_VALIDATE_INT
        );

        // Memeriksa validitas ID
        if (!$id || $id < 1) {

            $this->redirectWithMessage(
                "ID jadwal tidak valid.",
                "danger"
            );
        }

        // Memastikan jadwal masih tersedia
        $schedule = $this->scheduleModel->getById($id);

        if (!$schedule) {

            $this->redirectWithMessage(
                "Data jadwal tidak ditemukan.",
                "danger"
            );
        }

        // Mengambil data terbaru dari formulir
        $data = $this->getFormData();

        // Memeriksa kelengkapan dan validitas data
        if (!$this->validateData($data)) {

            $this->redirectWithMessage(
                "Data jadwal tidak valid. Periksa kembali formulir.",
                "danger",
                "index.php?action=schedule_edit&id=" . $id
            );
        }

        try {

            // Memanggil Model untuk memperbarui jadwal
            $success = $this->scheduleModel->update($id, $data);

            if ($success) {

                $this->redirectWithMessage(
                    "Jadwal mata kuliah berhasil diperbarui."
                );
            }

        } catch (mysqli_sql_exception $e) {

            // Menangani kegagalan perubahan data
            $this->redirectWithMessage(
                "Jadwal mata kuliah gagal diperbarui.",
                "danger",
                "index.php?action=schedule_edit&id=" . $id
            );
        }

        // Menampilkan pesan jika perubahan gagal
        $this->redirectWithMessage(
            "Jadwal mata kuliah gagal diperbarui.",
            "danger",
            "index.php?action=schedule_edit&id=" . $id
        );
    }

    // =========================
    // DELETE
    // =========================

    // Method untuk memproses penghapusan jadwal
    public function delete() {

        // Memastikan request menggunakan POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header("Location: index.php?action=schedule_index");

            exit;
        }

        // Memeriksa token keamanan
        $this->verifyCsrf();

        // Mengambil ID jadwal dari formulir
        $id = filter_input(
            INPUT_POST,
            'id',
            FILTER_VALIDATE_INT
        );

        // Memeriksa validitas ID
        if (!$id || $id < 1) {

            $this->redirectWithMessage(
                "ID jadwal tidak valid.",
                "danger"
            );
        }

        // Memanggil Model untuk menghapus jadwal
        if ($this->scheduleModel->delete($id)) {

            $this->redirectWithMessage(
                "Jadwal mata kuliah berhasil dihapus."
            );

        } else {

            $this->redirectWithMessage(
                "Jadwal mata kuliah gagal dihapus atau tidak ditemukan.",
                "danger"
            );
        }
    }
}
