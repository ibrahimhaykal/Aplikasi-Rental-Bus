<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Pagination variables
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$records_per_page = 5; // Number of records per page

// Calculate the limit clause in the SQL query
$offset = ($page - 1) * $records_per_page;

// Include database connection
include '../include/koneksi.php';

// Query to fetch invoice data with pagination
$sql = "SELECT * FROM invoice LIMIT $offset, $records_per_page";
$result = $db->query($sql);

// Count total number of records for pagination
$total_rows = $db->query("SELECT COUNT(*) AS total FROM invoice")->fetch_assoc()['total'];

$db->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Dashboard - Invoice Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #wrapper.toggled #sidebar-wrapper {
            margin-left: 0;
        }

        #sidebar-wrapper {
            margin-left: -250px;
            transition: all 0.3s ease;
            height: 100vh; /* Make sidebar full height */
        }

        #content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensure footer stays at the bottom */
        }

        footer {
            margin-top: auto; /* Push footer to the bottom */
        }

        .pagination {
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }

        .pagination a {
            color: black;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
            border: 1px solid #ddd;
            margin: 0 4px;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }

        .table-responsive {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            padding: 12px; /* Increased padding for better readability */
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .btn-group .btn {
            margin-right: 5px;
            white-space: nowrap; /* Prevent button text from wrapping */
        }
    </style>
</head>
<body>
    <?php include '../include/navbar.php' ?>
    <div class="d-flex" id="wrapper">
        <?php include '../include/sidebar.php' ?>
        <div id="content" class="container-fluid">
            <main class="p-4">
                <div class="container">
                    <h2 class="mt-4">Status Penyewaan Bus</h2>
                    <div class="table-responsive">
                        <table class="table table-light table-hover">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Tanggal Penyewaan</th>
                                    <th>Tanggal Pengembalian</th>
                                    <th>TNKB</th>
                                    <th>ID Pemesan</th>
                                    <th>Total Harga</th>
                                    <th>Status Pesanan</th>
                                    <th>Status Pembayaran</th>
                                    <th>Bukti Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bukti_pembayaran_link = !empty($row['bukti_pembayaran']) ? '../uploads/bukti_pembayaran/' . basename($row['bukti_pembayaran']) : '#';
        $bukti_pembayaran_text = !empty($row['bukti_pembayaran']) ? 'View Proof' : 'No Proof';
        $status_pesanan_class = $row['status_pesanan'] == 'confirmed' ? 'text-success' : ($row['status_pesanan'] == 'Pending' ? 'text-danger' : '');
        $status_pembayaran_class = $row['status_pembayaran'] == 'paid' ? 'text-success' : ($row['status_pembayaran'] == 'Pending' ? 'text-danger' : '');
        echo "<tr>
            <td>{$row['kode']}</td>
            <td>{$row['tglpenyewaan']}</td>
            <td>{$row['tglpengembalian']}</td>
            <td>{$row['tnkb']}</td>
            <td>{$row['user_id']}</td>
            <td>{$row['totalharga']}</td>
            <td class='$status_pesanan_class'>{$row['status_pesanan']}</td>
            <td class='$status_pembayaran_class'>{$row['status_pembayaran']}</td>
            <td><a href='$bukti_pembayaran_link' target='_blank'>$bukti_pembayaran_text</a></td>
            <td>
                <div class='btn-group'>
                    <a href='ubah_penyewaan.php?kode={$row['kode']}' class='btn btn-warning btn-sm'>Edit</a>
                    <a class='btn btn-danger btn-sm' href='hapus_penyewaan.php?kode={$row['kode']}' onclick='return confirm(\"Yakin hapus?\")'>Hapus</a>
                    <a class='btn btn-success btn-sm' href='validasi_status_pesanan.php?kode={$row['kode']}' onclick='return confirm(\"Yakin validasi status pesanan?\")'>Validasi Pesanan</a>
                    <a class='btn btn-info btn-sm' href='validasi_status_pembayaran.php?kode={$row['kode']}' onclick='return confirm(\"Yakin validasi status pembayaran?\")'>Validasi Pembayaran</a>
                </div>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='10' class='text-center'>Tidak ada data</td></tr>";
}
?>

                            </tbody>
                        </table>
                    </div>
                    <div class="pagination">
                    <?php for ($i = 1; $i <= ceil($total_rows / $records_per_page); $i++): ?>
                        <a href="?page=<?= $i ?>" class="page-link <?= $page == $i ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('wrapper').classList.toggle('toggled');
        });
    </script>
</body>
</html>
