<?php

// Memanggil file Database.php
require_once __DIR__ . "/Database.php";

// Membuat class Lecturer sebagai Model
class Lecturer {
    // Menyimpan objek koneksi database
    private $conn;

    // Constructor menerima koneksi database
    public function __construct($conn) {
        // Menyimpan koneksi ke dalam atribut class
        $this->conn = $conn;
    }

    // Method untuk mengambil seluruh data dosen
    public function getAll() {
        // Membuat perintah SQL untuk mengambil semua data dosen
        $sql = "SELECT * FROM lecturers ORDER BY id ASC";
        // Menjalankan query
        $result = $this->conn->query($sql);
        // Menyiapkan array untuk menampung data dosen
        $lecturers = [];

        // Mengambil data satu per satu
        while ($row = $result->fetch_assoc()) {
            // Memasukkan data ke dalam array
            $lecturers[] = $row;
        }

        // Mengembalikan seluruh data dosen
        return $lecturers;
    }

    // Method untuk mengambil satu data dosen berdasarkan ID
    public function getById($id) {
        // Membuat query dengan placeholder
        $sql = "SELECT * FROM lecturers WHERE id = ?";
        // Menyiapkan prepared statement
        $stmt = $this->conn->prepare($sql);
        // Mengikat parameter ID sebagai integer
        $stmt->bind_param("i", $id);
        // Menjalankan query
        $stmt->execute();
        // Mengambil hasil query
        $result = $stmt->get_result();
        // Mengambil satu baris data
        $lecturer = $result->fetch_assoc();
        // Menutup statement
        $stmt->close();

        // Mengembalikan data dosen
        return $lecturer;
    }

    // Method untuk menambahkan data dosen
    public function create($data) {
        // Membuat query INSERT dengan placeholder
        $sql = "INSERT INTO lecturers (name, nidn, phone, join_date)
                VALUES (?, ?, ?, ?)";

        // Menyiapkan prepared statement
        $stmt = $this->conn->prepare($sql);

        // Mengambil data dari array
        $name = $data['name'];
        $nidn = $data['nidn'];
        $phone = $data['phone'];
        $joinDate = $data['join_date'];

        // Mengikat empat parameter bertipe string
        $stmt->bind_param("ssss", $name, $nidn, $phone, $joinDate);
        // Menjalankan perintah INSERT
        $success = $stmt->execute();
        // Menutup statement
        $stmt->close();

        // Mengembalikan hasil operasi
        return $success;
    }

    // Method untuk mengubah data dosen
    public function update($id, $data) {
        // Membuat query UPDATE dengan placeholder
        $sql = "UPDATE lecturers
                SET name = ?, nidn = ?, phone = ?, join_date = ?
                WHERE id = ?";

        // Menyiapkan prepared statement
        $stmt = $this->conn->prepare($sql);

        // Mengambil data dari array
        $name = $data['name'];
        $nidn = $data['nidn'];
        $phone = $data['phone'];
        $joinDate = $data['join_date'];

        // Mengikat parameter
        // Empat data pertama string dan ID berupa integer
        $stmt->bind_param("ssssi", $name, $nidn, $phone, $joinDate, $id);
        // Menjalankan perintah UPDATE
        $success = $stmt->execute();
        // Menutup statement
        $stmt->close();

        // Mengembalikan hasil operasi
        return $success;
    }

    // Method untuk menghapus data dosen
    public function delete($id) {
        // Membuat query DELETE dengan placeholder
        $sql = "DELETE FROM lecturers WHERE id = ?";
        // Menyiapkan prepared statement
        $stmt = $this->conn->prepare($sql);
        // Mengikat parameter ID
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