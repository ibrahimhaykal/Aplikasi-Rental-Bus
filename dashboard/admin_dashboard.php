<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Database dbection
include '../include/koneksi.php';


// Query untuk mendapatkan data dari tabel pemesan, bus, dan invoice
$pemesan_sql = "SELECT * FROM pemesan";
$pemesan_result = $db->query($pemesan_sql);

$bus_sql = "SELECT * FROM bus";
$bus_result = $db->query($bus_sql);

$invoice_sql = "SELECT * FROM invoice";
$invoice_result = $db->query($invoice_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Dashboard - Bus Rental Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
<?php include '../include/navbar.php'; ?>
<div class="d-flex" id="wrapper">
    <?php include '../include/sidebar.php'; ?>
    <div id="content-wrapper" class="container-fluid">
        <main class="p-4">
            <h1 class="mt-5">Sistem Penyewaan Bus Pariwisata</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="row mb-4">
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Detail Pemesan
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID Pemesan</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Nomor Telepon</th>
                                        <th>Alamat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $pemesan_result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $row['id_pemesan']; ?></td>
                                        <td><?php echo $row['nama']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['nomor_telepon']; ?></td>
                                        <td><?php echo $row['alamat']; ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Detail Bus
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>TNKB</th>
                                        <th>Merek</th>
                                        <th>Jenis</th>
                                        <th>Kapasitas</th>
                                        <th>Fasilitas</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $bus_result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $row['tnkb']; ?></td>
                                        <td><?php echo $row['merek']; ?></td>
                                        <td><?php echo $row['jenis']; ?></td>
                                        <td><?php echo $row['kapasitas']; ?></td>
                                        <td><?php echo $row['fasilitas']; ?></td>
                                        <td><?php echo $row['harga']; ?></td>
                                        <td><?php echo $row['status']; ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Detail Invoice
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Tanggal Penyewaan</th>
                                        <th>Tanggal Pengembalian</th>
                                        <th>TNKB</th>
                                        <th>User ID</th>
                                        <th>Total Harga</th>
                                        <th>Status Pembayaran</th>
                                        <th>Status Pesanan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $invoice_result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $row['kode']; ?></td>
                                        <td><?php echo $row['tglpenyewaan']; ?></td>
                                        <td><?php echo $row['tglpengembalian']; ?></td>
                                        <td><?php echo $row['tnkb']; ?></td>
                                        <td><?php echo $row['user_id']; ?></td>
                                        <td><?php echo $row['totalharga']; ?></td>
                                        <td><?php echo $row['status_pembayaran']; ?></td>
                                        <td><?php echo $row['status_pesanan']; ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
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
    // Area Chart Example
    var ctx = document.getElementById("myAreaChart").getContext('2d');
    var myAreaChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: "Rentals",
                data: [30, 50, 40, 60, 80, 90, 70, 100, 110, 130, 150, 170],
                backgroundColor: "rgba(2,117,216,0.2)",
                borderColor: "rgba(2,117,216,1)",
            }]
        }
    });

    // Bar Chart Example
    var ctx = document.getElementById("myBarChart").getContext('2d');
    var myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: "Rentals",
                data: [30, 50, 40, 60, 80, 90, 70, 100, 110, 130, 150, 170],
                backgroundColor: "rgba(2,117,216,0.2)",
                borderColor: "rgba(2,117,216,1)",
            }]
        }
    });

    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.getElementById('wrapper').classList.toggle('toggled');
    });
</script>
</body>
</html>

<?php
$db->close();
?>
