
<?php

// Memanggil file Database.php
require_once __DIR__ . "/Database.php";

// Membuat class CourseSchedule sebagai Model
class CourseSchedule {

    // Menyimpan objek koneksi database
    private $conn;

    // Constructor menerima koneksi database
    public function __construct($conn) {

        // Menyimpan koneksi ke dalam atribut class
        $this->conn = $conn;
    }

    // =========================
    // READ
    // =========================

    // Method untuk mengambil seluruh data jadwal
    public function getAll() {

        // Membuat query untuk mengambil data jadwal,
        // nama mata kuliah, kode mata kuliah, dan dosen pengampu
        $sql = "SELECT
                    cs.id,
                    cs.course_id,
                    cs.day_of_week,
                    cs.start_time,
                    cs.end_time,
                    cs.room,
                    c.course_name,
                    c.course_code,
                    l.name AS lecturer_name
                FROM course_schedules cs
                INNER JOIN courses c
                    ON cs.course_id = c.id
                INNER JOIN lecturers l
                    ON c.lecturer_id = l.id
                ORDER BY
                    FIELD(
                        cs.day_of_week,
                        'Senin',
                        'Selasa',
                        'Rabu',
                        'Kamis',
                        'Jumat',
                        'Sabtu'
                    ),
                    cs.start_time ASC";

        // Menyiapkan prepared statement
        $stmt = $this->conn->prepare($sql);

        // Menjalankan query
        $stmt->execute();

        // Mengambil hasil query
        $result = $stmt->get_result();

        // Menampung seluruh data ke dalam array
        $schedules = $result->fetch_all(MYSQLI_ASSOC);

        // Menutup statement
        $stmt->close();

        // Mengembalikan seluruh data jadwal
        return $schedules;
    }

    // Method untuk mengambil satu jadwal berdasarkan ID
    public function getById($id) {

        // Membuat query untuk mencari jadwal berdasarkan ID
        $sql = "SELECT
                    cs.id,
                    cs.course_id,
                    cs.day_of_week,
                    cs.start_time,
                    cs.end_time,
                    cs.room,
                    c.course_name,
                    c.course_code,
                    l.name AS lecturer_name
                FROM course_schedules cs
                INNER JOIN courses c
                    ON cs.course_id = c.id
                INNER JOIN lecturers l
                    ON c.lecturer_id = l.id
                WHERE cs.id = ?";

        // Menyiapkan prepared statement
        $stmt = $this->conn->prepare($sql);

        // Mengikat parameter ID sebagai integer
        $stmt->bind_param("i", $id);

        // Menjalankan query
        $stmt->execute();

        // Mengambil hasil query
        $result = $stmt->get_result();

        // Mengambil satu baris data
        $schedule = $result->fetch_assoc();

        // Menutup statement
        $stmt->close();

        // Mengembalikan data jadwal
        return $schedule;
    }

    // =========================
    // CREATE
    // =========================

    // Method untuk menambahkan jadwal mata kuliah
    public function create($data) {

        // Membuat query INSERT dengan placeholder
        $sql = "INSERT INTO course_schedules
                (course_id, day_of_week, start_time, end_time, room)
                VALUES (?, ?, ?, ?, ?)";

        // Menyiapkan prepared statement
        $stmt = $this->conn->prepare($sql);

        // Mengambil data dari array
        $courseId = (int) $data['course_id'];
        $dayOfWeek = $data['day_of_week'];
        $startTime = $data['start_time'];
        $endTime = $data['end_time'];
        $room = $data['room'];

        // Mengikat parameter
        // Course ID berupa integer, sisanya berupa string
        $stmt->bind_param(
            "issss",
            $courseId,
            $dayOfWeek,
            $startTime,
            $endTime,
            $room
        );

        // Menjalankan perintah INSERT
        $success = $stmt->execute();

        // Menutup statement
        $stmt->close();

        // Mengembalikan hasil operasi
        return $success;
    }

    // =========================
    // UPDATE
    // =========================

    // Method untuk mengubah data jadwal mata kuliah
    public function update($id, $data) {

        // Membuat query UPDATE dengan placeholder
        $sql = "UPDATE course_schedules
                SET course_id = ?,
                    day_of_week = ?,
                    start_time = ?,
                    end_time = ?,
                    room = ?
                WHERE id = ?";

        // Menyiapkan prepared statement
        $stmt = $this->conn->prepare($sql);

        // Mengambil data dari array
        $courseId = (int) $data['course_id'];
        $dayOfWeek = $data['day_of_week'];
        $startTime = $data['start_time'];
        $endTime = $data['end_time'];
        $room = $data['room'];

        // Mengikat parameter
        // Course ID dan ID jadwal berupa integer
        // Parameter lainnya berupa string
        $stmt->bind_param(
            "issssi",
            $courseId,
            $dayOfWeek,
            $startTime,
            $endTime,
            $room,
            $id
        );

        // Menjalankan perintah UPDATE
        $success = $stmt->execute();

        // Menutup statement
        $stmt->close();

        // Mengembalikan hasil operasi
        return $success;
    }

    // =========================
    // DELETE
    // =========================

    // Method untuk menghapus jadwal berdasarkan ID
    public function delete($id) {

        // Membuat query DELETE dengan placeholder
        $sql = "DELETE FROM course_schedules WHERE id = ?";

        // Menyiapkan prepared statement
        $stmt = $this->conn->prepare($sql);

        // Mengikat parameter ID sebagai integer
        $stmt->bind_param("i", $id);

        // Menjalankan perintah DELETE
        $success = $stmt->execute();

        // Memeriksa apakah ada data yang berhasil dihapus
        $deleted = $success && $stmt->affected_rows > 0;

        // Menutup statement
        $stmt->close();

        // Mengembalikan hasil penghapusan
        return $deleted;
    }
}
