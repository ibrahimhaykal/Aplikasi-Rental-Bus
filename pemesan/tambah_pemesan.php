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
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $alamat = $_POST['alamat'];

    simpan_data_pemesan($nama, $email, $nomor_telepon, $alamat);
}

function simpan_data_pemesan($nama, $email, $nomor_telepon, $alamat) {
    global $db;
    $sql = "INSERT INTO pemesan (nama, email, nomor_telepon, alamat) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssss", $nama, $email, $nomor_telepon, $alamat);

    if ($stmt->execute()) {
        header("Location: admin_pemesan.php");
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
            <h2 class="mt-4">Tambah Pemesan</h2>
    <form action="" method="POST" class="row g-3">
        <div class="col-md-6">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
        </div>
        <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="col-md-6">
            <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
            <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon">
        </div>
        <div class="col-md-6">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
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
