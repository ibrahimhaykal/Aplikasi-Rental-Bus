<?php
session_start();

// Check if the user is logged in and has the role 'user'
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit();
}

// Database connection
include '../include/koneksi.php';

// Get invoice data from the database based on user_id
$user_id = $_SESSION['user_id']; // Ensure user_id is stored in the session upon login
$records_per_page = 10; // Number of records per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start_from = ($page - 1) * $records_per_page;

$sql = $db->prepare("SELECT * FROM invoice WHERE user_id = ? LIMIT ?, ?");
$sql->bind_param("iii", $user_id, $start_from, $records_per_page);
$sql->execute();
$result = $sql->get_result();

$invoices = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $invoices[] = $row;
    }
}

// Calculate total pages
$total_sql = $db->prepare("SELECT COUNT(*) FROM invoice WHERE user_id = ?");
$total_sql->bind_param("i", $user_id);
$total_sql->execute();
$total_result = $total_sql->get_result();
$total_rows = $total_result->fetch_row()[0];

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Customer - Status Penyewaan Rental Bus</title>
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
    </style>
</head>
<body>
<?php include '../include/navbar.php'; ?>
<div class="d-flex" id="wrapper">
    <?php include '../include/sidebar_user.php'; ?>
    <div id="content" class="container-fluid">
        <main class="p-4">
            <div class="container">
                <h2 class="mt-4">Status Penyewaan Rental Bus</h2>
                <div class="table-responsive">
                    <table class="table table-light table-hover">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Tanggal Penyewaan</th>
                                <th>Tanggal Pengembalian</th>
                                <th>TNKB</th>
                                <th>Total Harga</th>
                                <th>Status Pesanan</th>
                                <th>Status Pembayaran</th>
                                <th>Bukti Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($invoices)): ?>
                                <?php foreach ($invoices as $row): ?>
                                    <tr>
                                        <?php 
                                        $status_pesanan_class = $row['status_pesanan'] == 'confirmed' ? 'text-success' : ($row['status_pesanan'] == 'pending' ? 'text-danger' : '');
                                        $status_pembayaran_class = $row['status_pembayaran'] == 'paid' ? 'text-success' : ($row['status_pembayaran'] == 'Pending' ? 'text-danger' : '');
                                        ?>
                                        <td><?= htmlspecialchars($row['kode']) ?></td>
                                        <td><?= htmlspecialchars($row['tglpenyewaan']) ?></td>
                                        <td><?= htmlspecialchars($row['tglpengembalian']) ?></td>
                                        <td><?= htmlspecialchars($row['tnkb']) ?></td>
                                        <td><?= htmlspecialchars($row['totalharga']) ?></td>
                                        <td class="<?= $status_pesanan_class ?>"><?= htmlspecialchars($row['status_pesanan']) ?></td>
                                        <td class="<?= $status_pembayaran_class ?>"><?= htmlspecialchars($row['status_pembayaran']) ?></td>
                                        <td>
                                            <?php if ($row['status_pembayaran'] == 'waiting'): ?>
                                                <i class="fas fa-hourglass-half"></i>
                                            <?php elseif ($row['status_pesanan'] == 'Sudah Divalidasi' && $row['status_pembayaran'] == 'Pembayaran Berhasil'): ?>
                                                <a href="print_invoice.php?kode=<?= htmlspecialchars($row['kode']) ?>" class="btn btn-success btn-sm">Cetak Invoice</a>
                                            <?php else: ?>
                                                <form action="upload_bukti_pembayaran.php" method="post" enctype="multipart/form-data">
                                                    <input type="hidden" name="kode" value="<?= htmlspecialchars($row['kode']) ?>">
                                                    <input type="file" name="bukti_pembayaran" accept="image/*" required>
                                                    <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-center">Tidak ada data</td></tr>
                            <?php endif; ?>
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
                    <div class="text-muted">Copyright &copy; Your Website <?= date('Y') ?></div>
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
