<?php
session_start();
cek_sesi_dan_role();

include '../include/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    proses_form();
}

function cek_sesi_dan_role() {
    if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
        header("Location: login.php");
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

    $gambar_bus = upload_gambar();

    simpan_data_bus($tnkb, $merek, $jenis, $kapasitas, $fasilitas, $harga, $status, $gambar_bus);
}

function upload_gambar() {
    if (isset($_FILES['gambar_bus']) && $_FILES['gambar_bus']['error'] == UPLOAD_ERR_OK) {
        return file_get_contents($_FILES['gambar_bus']['tmp_name']);
    } else {
        return NULL;
    }
}

function simpan_data_bus($tnkb, $merek, $jenis, $kapasitas, $fasilitas, $harga, $status, $gambar_bus) {
    global $db;
    $sql = "INSERT INTO bus (tnkb, merek, jenis, kapasitas, fasilitas, harga, status, gambar_bus) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sssissss", $tnkb, $merek, $jenis, $kapasitas, $fasilitas, $harga, $status, $gambar_bus);

    if ($stmt->execute()) {
        header("Location: admin_bus.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $db->error;
    }

    $stmt->close();
    $db->close();
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
<?php include '../include/navbar.php'?>
    <div class="d-flex toggled" id="wrapper">
        <?php include '../include/sidebar.php' ?>
        <div id="content" class="container-fluid">
            <main class="p-4">
            <h2 class="mt-4">Tambah Bus</h2>
    <form action="" method="POST" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-6">
            <label for="tnkb" class="form-label">TNKB</label>
            <input type="text" class="form-control" id="tnkb" name="tnkb" required>
        </div>
        <div class="col-md-6">
            <label for="merek" class="form-label">Merek</label>
            <input type="text" class="form-control" id="merek" name="merek" required>
        </div>
        <div class="col-md-6">
            <label for="jenis" class="form-label">Jenis</label>
            <input type="text" class="form-control" id="jenis" name="jenis" required>
        </div>
        <div class="col-md-6">
            <label for="kapasitas" class="form-label">Kapasitas</label>
            <input type="number" class="form-control" id="kapasitas" name="kapasitas" required>
        </div>
        <div class="col-md-6">
            <label for="fasilitas" class="form-label">Fasilitas</label>
            <input type="text" class="form-control" id="fasilitas" name="fasilitas" required>
        </div>
        <div class="col-md-6">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" class="form-control" id="harga" name="harga" required>
        </div>
        <div class="col-md-6">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="Tersedia">Tersedia</option>
                <option value="Tidak Tersedia">Tidak Tersedia</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="gambar_bus" class="form-label">Gambar Bus</label>
            <input type="file" class="form-control" id="gambar_bus" name="gambar_bus" accept="image/*" required>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Simpan</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('wrapper').classList.toggle('toggled');
        });
    </script>
</body>
</html>
