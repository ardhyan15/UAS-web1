<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama_makanan'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];

    // Validasi gambar
    $gambar = $_FILES['gambar'];
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $max_file_size = 2 * 1024 * 1024; // Maksimal 2MB
    $upload_dir = 'uploads/';

    // Periksa jika file gambar diunggah
    if ($gambar['error'] == UPLOAD_ERR_OK) {
        $file_extension = strtolower(pathinfo($gambar['name'], PATHINFO_EXTENSION));
        $file_size = $gambar['size'];
        $file_temp = $gambar['tmp_name'];
        $new_file_name = uniqid('img_', true) . '.' . $file_extension; // Nama file unik
        $target = $upload_dir . $new_file_name;

        // Validasi ekstensi file
        if (in_array($file_extension, $allowed_extensions)) {
            // Validasi ukuran file
            if ($file_size <= $max_file_size) {
                if (move_uploaded_file($file_temp, $target)) {
                    // Menggunakan prepared statement untuk mencegah SQL Injection
                    $stmt = $conn->prepare("INSERT INTO foodlist (nama_makanan, harga, deskripsi, gambar) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("siss", $nama, $harga, $deskripsi, $target);

                    if ($stmt->execute()) {
                        echo "<script>alert('Data berhasil ditambahkan!'); window.location='index.php';</script>";
                    } else {
                        echo "<script>alert('Gagal menyimpan data ke database!');</script>";
                    }

                    $stmt->close();
                } else {
                    echo "<script>alert('Gagal mengunggah file!');</script>";
                }
            } else {
                echo "<script>alert('Ukuran file terlalu besar! Maksimal 2MB.');</script>";
            }
        } else {
            echo "<script>alert('Format file tidak valid! Hanya JPG, JPEG, PNG, GIF yang diperbolehkan.');</script>";
        }
    } else {
        echo "<script>alert('Harap unggah file gambar!');</script>";
    }
}
?>
