<?php
// koneksi.example.php
// Salin file ini menjadi 'koneksi.php' di server/lokal dan sesuaikan kredensialnya.

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_pembinaan_almiftah";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// API Key Konfigurasi
$groqApiKey = 'MASUKKAN_GROQ_API_KEY_ANDA_DISINI';
?>
