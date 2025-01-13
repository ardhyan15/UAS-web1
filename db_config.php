<?php
// Konfigurasi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kulinerku";

// Mengaktifkan pelaporan kesalahan untuk debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Membuat koneksi ke database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Mengatur charset untuk koneksi
    $conn->set_charset("utf8");

    echo "Koneksi berhasil!";
} catch (mysqli_sql_exception $e) {
    // Menangani error koneksi
    die("Koneksi gagal: " . $e->getMessage());
}
?>
