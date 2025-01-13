<?php
include 'db_config.php';

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Validasi bahwa ID adalah angka
    if (filter_var($id, FILTER_VALIDATE_INT)) {
        // Cek apakah data dengan ID tersebut ada di database
        $stmt = $conn->prepare("SELECT * FROM foodlist WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Data ditemukan, lanjutkan penghapusan
            $stmt = $conn->prepare("DELETE FROM foodlist WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo "<script>alert('Data berhasil dihapus!'); window.location='index.php';</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan saat menghapus data!'); window.location='index.php';</script>";
            }
        } else {
            echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('ID tidak valid!'); window.location='index.php';</script>";
    }
}
?>
