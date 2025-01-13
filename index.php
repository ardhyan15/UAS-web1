<?php
include 'db_config.php';

// Ambil data dari tabel foodlist
$result = $conn->query("SELECT * FROM foodlist");
if (!$result) {
    die("Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kulinerku</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f1f1f1;
            font-family: Arial, sans-serif;
        }
        h1 {
            color: #343a40;
            text-align: center;
            margin-bottom: 30px;
        }
        .table img {
            border-radius: 10px;
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
        .table .harga {
            width: 150px;
        }
        .table-hover tbody tr:hover {
            background-color: #ffc107;
            color: #fff;
        }
        .table td.deskripsi {
            text-align: left;
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-top: 50px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-utensils"></i> Kulinerku</h1>
        <a href="tambah.php" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Tambah Makanan</a>
        <table class="table table-hover table-bordered table-striped" id="myTable">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Makanan</th>
                    <th class="harga">Harga</th>
                    <th>Deskripsi</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_makanan']) ?></td>
                            <td class="harga">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                            <td class="deskripsi" title="<?= htmlspecialchars($row['deskripsi']) ?>">
                                <?= htmlspecialchars($row['deskripsi']) ?>
                            </td>
                            <td>
                                <?php if (file_exists($row['gambar'])): ?>
                                    <img src="<?= htmlspecialchars($row['gambar']) ?>" alt="Gambar <?= htmlspecialchars($row['nama_makanan']) ?>">
                                <?php else: ?>
                                    <img src="placeholder.jpg" alt="Placeholder">
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="proses.php?hapus=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        // Inisialisasi DataTables
        $(document).ready(function() {
            $('#myTable').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                lengthChange: false,
                ordering: true,
                order: [[1, 'asc']],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    }
                }
            });
        });
    </script>
</body>
</html>
