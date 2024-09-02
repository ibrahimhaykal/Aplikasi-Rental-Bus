<?php
session_start();
cek_sesi_dan_role();

include '../include/koneksi.php';

$data = fetch_data_bus();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['proses'])) {
    proses_form();
}

function cek_sesi_dan_role() {
    if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
        header("Location: login.php");
        exit();
    }
}

function fetch_data_bus() {
    global $db;
    if (isset($_GET['tnkb'])) {
        $tnkb = $_GET['tnkb'];
        $query = mysqli_query($db, "SELECT * FROM bus WHERE tnkb = '$tnkb'");
        $data = mysqli_fetch_array($query);
        if (!$data) {
            // Redirect if no data found
            header("Location: admin_bus.php");
            exit();
        }
        return $data;
    } else {
        // Redirect if no tnkb is set
        header("Location: admin_bus.php");
        exit();
    }
}

function proses_form() {
    global $db;
    $tnkb = $_POST['tnkb'];
    $merek = $_POST['merek'];
    $jenis = $_POST['jenis'];
    $kapasitas = $_POST['kapasitas'];
    $fasilitas = $_POST['fasilitas'];
    $harga = $_POST['harga'];
    $status = $_POST['status'];

    // Handle file upload for gambar_bus
    if (isset($_FILES['gambar_bus']) && $_FILES['gambar_bus']['error'] == 0) {
        $gambar_bus = addslashes(file_get_contents($_FILES['gambar_bus']['tmp_name']));
        $update_query = "UPDATE bus SET merek='$merek', jenis='$jenis', kapasitas='$kapasitas', fasilitas='$fasilitas', harga='$harga', status='$status', gambar_bus='$gambar_bus' WHERE tnkb='$tnkb'";
    } else {
        $update_query = "UPDATE bus SET merek='$merek', jenis='$jenis', kapasitas='$kapasitas', fasilitas='$fasilitas', harga='$harga', status='$status' WHERE tnkb='$tnkb'";
    }

    mysqli_query($db, $update_query);
    header("Location: admin_bus.php"); // Redirect to admin_bus.php after update
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Dashboard - Bus Rental Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../include/navbar.php'; ?>
    <div class="d-flex toggled" id="wrapper">
        <?php include '../include/sidebar.php'; ?>
        <div id="content" class="container-fluid">
            <main class="p-4">
                <h2 class="mt-4">Edit Bus</h2>
                <form action="" method="POST" enctype="multipart/form-data" class="row g-3">
                    <div class="col-md-6">
                        <label for="tnkb" class="form-label">TNKB</label>
                        <input type="text" class="form-control" id="tnkb" name="tnkb" value="<?php echo isset($data['tnkb']) ? htmlspecialchars($data['tnkb']) : ''; ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="merek" class="form-label">Merek</label>
                        <input type="text" class="form-control" id="merek" name="merek" value="<?php echo isset($data['merek']) ? htmlspecialchars($data['merek']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="jenis" class="form-label">Jenis</label>
                        <input type="text" class="form-control" id="jenis" name="jenis" value="<?php echo isset($data['jenis']) ? htmlspecialchars($data['jenis']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="kapasitas" class="form-label">Kapasitas</label>
                        <input type="number" class="form-control" id="kapasitas" name="kapasitas" value="<?php echo isset($data['kapasitas']) ? htmlspecialchars($data['kapasitas']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="fasilitas" class="form-label">Fasilitas</label>
                        <input type="text" class="form-control" id="fasilitas" name="fasilitas" value="<?php echo isset($data['fasilitas']) ? htmlspecialchars($data['fasilitas']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" value="<?php echo isset($data['harga']) ? htmlspecialchars($data['harga']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Tersedia" <?php if(isset($data['status']) && $data['status'] == 'Tersedia') echo 'selected'; ?>>Tersedia</option>
                            <option value="Tidak Tersedia" <?php if(isset($data['status']) && $data['status'] == 'Tidak Tersedia') echo 'selected'; ?>>Tidak Tersedia</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="gambar_bus" class="form-label">Gambar Bus</label>
                        <input type="file" class="form-control" id="gambar_bus" name="gambar_bus" accept="image/*">
                        <?php if (isset($data['gambar_bus']) && $data['gambar_bus']): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($data['gambar_bus']); ?>" alt="Bus Image" style="width: 100px; height: auto; margin-top: 10px;">
                        <?php endif; ?>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="proses" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
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
