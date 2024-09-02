<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit();
}

// Database connection
include '../include/koneksi.php';

// Fetch bus offerings
$sql_buses = "SELECT * FROM bus";
$result_buses = $db->query($sql_buses);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>User Dashboard - Bus Rental Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../include/navbar.php' ?>
<div class="d-flex toggled" id="wrapper">
<?php include '../include/sidebar_user.php' ?>
    <div id="content" class="container-fluid">
        <main class="p-4">
            <h1 class="mt-5">Penawaran Rental Bus</h1>
            <p class="lead">Lihat penawaran rental bus kami di bawah ini:</p>
            <div class="row">
                <?php
                if ($result_buses->num_rows > 0) {
                    while ($bus = $result_buses->fetch_assoc()) {
                        echo '<div class="col-md-4 mb-4">';
                        echo '<div class="card h-100">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . htmlspecialchars($bus['jenis']) . '</h5>';
                        echo '<p class="card-text">' . htmlspecialchars($bus['fasilitas']) . '</p>';
                        echo '<p class="card-text"><strong>Status:</strong> ' . htmlspecialchars($bus['status']) . '</p>';
                        echo '</div>';
                        echo '<div class="card-footer">';
                        if ($bus['status'] == 'Tersedia') {
                            echo '<a href="../penyewaan/formulir_penyewaan.php?tnkb=' . htmlspecialchars($bus['tnkb']) . '" class="btn btn-primary">Sewa Sekarang</a>';
                        } else {
                            echo '<button class="btn btn-secondary" disabled>Tidak Tersedia</button>';
                        }
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p class="lead">Tidak ada penawaran bus tersedia saat ini.</p>';
                }
                ?>
            </div>
            
            <h2 class="mt-5">Testimoni Pelanggan</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <p class="card-text">"Pelayanan yang luar biasa dan bus yang sangat nyaman!" - Ahmad</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <p class="card-text">"Pengalaman terbaik menyewa bus bersama keluarga." - Siti</p>
                        </div>
                    </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script>
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.getElementById('wrapper').classList.toggle('toggled');
    });
</script>
</body>
</html>
