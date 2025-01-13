<?php
include 'db_config.php';

// Validasi ID (integer)
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM foodlist WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Data tidak ditemukan!");
}

$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil dan validasi input
    $nama = htmlspecialchars(trim($_POST['nama_makanan']));
    $harga = filter_var($_POST['harga'], FILTER_VALIDATE_INT);
    $deskripsi = htmlspecialchars(trim($_POST['deskripsi']));
    $gambar_query = "";

    // Validasi data input
    if (!$nama || !$harga || !$deskripsi) {
        die("Data input tidak valid!");
    }

    // Proses upload gambar jika ada
    if (!empty($_FILES['gambar']['name'])) {
        $gambar = $_FILES['gambar']['name'];
        $target = "uploads/" . basename($gambar);
        $file_type = mime_content_type($_FILES['gambar']['tmp_name']);
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($file_type, $allowed_types)) {
            die("Format file tidak didukung! Hanya JPEG, PNG, dan GIF yang diperbolehkan.");
        }

        if ($_FILES['gambar']['size'] > 2 * 1024 * 1024) { // Maksimal 2MB
            die("Ukuran file terlalu besar! Maksimal 2MB.");
        }

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
            $gambar_query = ", gambar=?";
        } else {
            die("Gagal mengunggah gambar!");
        }
    }

    // Query Update Data
    $query = "UPDATE foodlist SET nama_makanan=?, harga=?, deskripsi=? $gambar_query WHERE id=?";
    $stmt = $conn->prepare($query);

    if ($gambar_query) {
        $stmt->bind_param("sisi", $nama, $harga, $deskripsi, $target, $id);
    } else {
        $stmt->bind_param("sisi", $nama, $harga, $deskripsi, $id);
    }

    if ($stmt->execute()) {
        echo "<script>
                alert('Data berhasil diperbarui!');
                window.location='index.php';
              </script>";
    } else {
        die("Error: " . $conn->error);
    }
}
?>
