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

// Query to fetch pemesan data with pagination
$sql = "SELECT * FROM pemesan LIMIT $offset, $records_per_page";
$result = $db->query($sql);

// Count total number of records for pagination
$total_rows = $db->query("SELECT COUNT(*) AS total FROM pemesan")->fetch_assoc()['total'];

$db->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Dashboard - Bus Rental Management</title>
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
            padding: 8px;
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
    </style>
</head>
<body>
    <?php include '../include/navbar.php' ?>
    <div class="d-flex" id="wrapper">
        <?php include '../include/sidebar.php' ?>
        <div id="content" class="container-fluid">
            <main class="p-4">
                <div class="container">
                    <h2 class="mt-4">Kelola Data Pemesan</h2>
                    <a href="tambah_pemesan.php" class="btn btn-primary my-3">Tambah Pemesan</a>
                    <div class="table-responsive">
                        <table class="table table-light table-hover">
                            <thead>
                                <tr>
                                    <th>ID Pemesan</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Nomor Telepon</th>
                                    <th>Alamat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                            <td>{$row['id_pemesan']}</td>
                                            <td>{$row['nama']}</td>
                                            <td>{$row['email']}</td>
                                            <td>{$row['nomor_telepon']}</td>
                                            <td>{$row['alamat']}</td>
                                            <td>
                                                <a href='ubah_pemesan.php?id_pemesan={$row['id_pemesan']}' class='btn btn-warning btn-sm'>Edit</a>
                                                <a class='btn btn-danger btn-sm' href='hapus_pemesan.php?id_pemesan={$row['id_pemesan']}' onclick='return confirm(\"Yakin hapus?\")'>Hapus</a>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>Tidak ada data</td></tr>";
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
