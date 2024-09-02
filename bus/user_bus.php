<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
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

// Search functionality
$search = isset($_GET['search']) ? $db->real_escape_string($_GET['search']) : '';

// Query to fetch bus data with pagination and search
$sql = "SELECT * FROM bus WHERE tnkb LIKE '%$search%' OR merek LIKE '%$search%' OR jenis LIKE '%$search%' OR fasilitas LIKE '%$search%' LIMIT $offset, $records_per_page";
$result = $db->query($sql);

// Count total number of records for pagination
$total_rows = $db->query("SELECT COUNT(*) AS total FROM bus WHERE tnkb LIKE '%$search%' OR merek LIKE '%$search%' OR jenis LIKE '%$search%'OR fasilitas LIKE '%$search%'")->fetch_assoc()['total'];

$db->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Bus Rental - Customer View</title>
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
        <?php include '../include/sidebar_user.php' ?>
        <div id="content" class="container-fluid">
            <main class="p-4">
                <div class="container">
                    <h2 class="mt-4">Daftar Bus</h2>
                    <!-- Search form -->
                    <form class="d-flex mb-3" method="get" action="">
                        <input class="form-control me-2" type="search" name="search" placeholder="Search" aria-label="Search" value="<?= htmlspecialchars($search) ?>">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-light table-hover">
                            <thead>
                                <tr>
                                    <th>TNKB</th>
                                    <th>Merek</th>
                                    <th>Jenis</th>
                                    <th>Kapasitas</th>
                                    <th>Fasilitas</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Gambar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $gambar = base64_encode($row['gambar_bus']);
                                        $gambar_src = $gambar ? "data:image/jpeg;base64,$gambar" : 'placeholder.png'; // Use a placeholder image if no image is available
                                        $status_class = $row['status'] == 'Tersedia' ? 'text-success' : 'text-danger';
                                        echo "<tr>
                                            <td>{$row['tnkb']}</td>
                                            <td>{$row['merek']}</td>
                                            <td>{$row['jenis']}</td>
                                            <td>{$row['kapasitas']}</td>
                                            <td>{$row['fasilitas']}</td>
                                            <td>{$row['harga']}</td>
                                            <td class='$status_class'>{$row['status']}</td>
                                            <td><img src='$gambar_src' alt='Bus Image' style='width: 100px; height: auto;'></td>
                                            <td>";
                                        if ($row['status'] == 'Tersedia') {
                                            echo "<a href='../penyewaan/formulir_penyewaan.php?tnkb={$row['tnkb']}' class='btn btn-primary btn-sm'>Sewa</a>";
                                        } else {
                                            echo "<i class='fas fa-ban text-danger' title='Tidak Tersedia'></i>";
                                        }
                                        echo "</td></tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='9' class='text-center'>Bus Tidak Ada</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination">
                    <?php for ($i = 1; $i <= ceil($total_rows / $records_per_page); $i++): ?>
                        <a href="?page=<?= $i ?>&search=<?= htmlspecialchars($search) ?>" class="page-link <?= $page == $i ? 'active' : '' ?>"><?= $i ?></a>
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
