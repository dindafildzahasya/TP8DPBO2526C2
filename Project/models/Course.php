<?php

// Memanggil file Database.php
require_once __DIR__ . "/Database.php";

// Membuat class Course sebagai Model
class Course {
    // Menyimpan objek koneksi database
    private $conn;

    // Constructor menerima koneksi database
    public function __construct($conn) {
        // Menyimpan koneksi ke dalam atribut class
        $this->conn = $conn;
    }

    // Method untuk mengambil seluruh data mata kuliah
    public function getAll() {

        // Membuat query untuk mengambil data mata kuliah
        // sekaligus nama dosen yang mengajar
        $sql = "SELECT c.id, c.course_name, c.course_code, c.sks, c.lecturer_id, l.name AS lecturer_name
                FROM courses c
                INNER JOIN lecturers l
                ON c.lecturer_id = l.id
                ORDER BY c.id ASC";

        // Menyiapkan prepared statement
        $stmt = $this->conn->prepare($sql);
        // Menjalankan query
        $stmt->execute();
        // Mengambil hasil query
        $result = $stmt->get_result();
        // Mengubah seluruh hasil query menjadi array
        $courses = $result->fetch_all(MYSQLI_ASSOC);
        // Menutup statement
        $stmt->close();

        // Mengembalikan seluruh data mata kuliah
        return $courses;
    }

    // Method untuk mengambil satu data mata kuliah berdasarkan ID
    public function getById($id) {
        // Membuat query untuk mencari mata kuliah berdasarkan ID
        $sql = "SELECT c.id, c.course_name, c.course_code, c.sks, c.lecturer_id, l.name AS lecturer_name
                FROM courses c
                INNER JOIN lecturers l
                ON c.lecturer_id = l.id
                WHERE c.id = ?";

        // Menyiapkan prepared statement
        $stmt = $this->conn->prepare($sql);
        // Mengikat parameter ID sebagai integer
        $stmt->bind_param("i", $id);
        // Menjalankan query
        $stmt->execute();
        // Mengambil hasil query
        $result = $stmt->get_result();
        // Mengambil satu baris data
        $course = $result->fetch_assoc();
        // Menutup statement
        $stmt->close();

        // Mengembalikan data mata kuliah
        return $course;
    }

    // Method untuk menambahkan data mata kuliah
    public function create($data) {
        // Membuat query INSERT dengan placeholder
        $sql = "INSERT INTO courses
                (course_name, course_code, sks, lecturer_id)
                VALUES (?, ?, ?, ?)";

        // Menyiapkan prepared statement
        $stmt = $this->conn->prepare($sql);

        // Mengambil data dari array
        $courseName = $data['course_name'];
        $courseCode = $data['course_code'];
        $sks = (int) $data['sks'];
        $lecturerId = (int) $data['lecturer_id'];

        // Mengikat parameter
        // Dua parameter pertama string
        // Dua parameter berikutnya integer
        $stmt->bind_param("ssii", $courseName, $courseCode, $sks, $lecturerId);
        // Menjalankan perintah INSERT
        $success = $stmt->execute();
        // Menutup statement
        $stmt->close();

        // Mengembalikan hasil operasi
        return $success;
    }

    // Method untuk mengubah data mata kuliah
    public function update($id, $data) {
        // Membuat query UPDATE dengan placeholder
        $sql = "UPDATE courses
                SET course_name = ?, course_code = ?, sks = ?, lecturer_id = ?
                WHERE id = ?";

        // Menyiapkan prepared statement
        $stmt = $this->conn->prepare($sql);

        // Mengambil data dari array
        $courseName = $data['course_name'];
        $courseCode = $data['course_code'];
        $sks = (int) $data['sks'];
        $lecturerId = (int) $data['lecturer_id'];

        // Mengikat parameter
        // Dua parameter pertama string
        // Tiga parameter berikutnya integer
        $stmt->bind_param( "ssiii", $courseName, $courseCode, $sks, $lecturerId, $id);
        // Menjalankan perintah UPDATE
        $success = $stmt->execute();
        // Menutup statement
        $stmt->close();

        // Mengembalikan hasil operasi
        return $success;
    }

    // Method untuk menghapus data mata kuliah
    public function delete($id) {
        // Membuat query DELETE dengan placeholder
        $sql = "DELETE FROM courses WHERE id = ?";
        // Menyiapkan prepared statement
        $stmt = $this->conn->prepare($sql);
        // Mengikat parameter ID sebagai integer
        $stmt->bind_param("i", $id);
        // Menjalankan perintah DELETE
        $success = $stmt->execute();
        // Memeriksa apakah ada data yang terhapus
        $deleted = $success && $stmt->affected_rows > 0;
        // Menutup statement
        $stmt->close();

        // Mengembalikan hasil penghapusan
        return $deleted;
    }
}
