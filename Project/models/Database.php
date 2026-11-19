<?php

// Membuat class Database untuk mengatur koneksi database
class Database {
    // Menyimpan konfigurasi database
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "tp_mvc25";
    // Menyimpan objek koneksi database
    private $conn;

    // Constructor dijalankan ketika objek Database dibuat
    public function __construct() {
        // Mengaktifkan pelaporan error MySQLi
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            // Membuat koneksi ke database MySQL
            $this->conn = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->database
            );
            // Mengatur karakter agar mendukung UTF-8
            $this->conn->set_charset("utf8mb4");

        } catch (mysqli_sql_exception $e) {
            // Menampilkan pesan jika koneksi gagal
            die("Koneksi database gagal: " . $e->getMessage());
        }
    }

    // Method untuk mendapatkan koneksi database
    public function getConnection() {
        return $this->conn;
    }
}