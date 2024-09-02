<?php
session_start();
cek_sesi_dan_role();

include '../include/koneksi.php';

$data = fetch_data_pemesan();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['proses'])) {
    proses_form();
}

function cek_sesi_dan_role() {
    if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
        header("Location: login.php");
        exit();
    }
}

function fetch_data_pemesan() {
    global $db;
    if (isset($_GET['id_pemesan'])) {
        $id_pemesan = $_GET['id_pemesan'];
        $query = mysqli_query($db, "SELECT * FROM pemesan WHERE id_pemesan = '$id_pemesan'");
        $data = mysqli_fetch_array($query);
        if (!$data) {
            // Redirect if no data found
            header("Location: admin_pemesan.php");
            exit();
        }
        return $data;
    } else {
        // Redirect if no id_pemesan is set
        header("Location: admin_pemesan.php");
        exit();
    }
}

function proses_form() {
    global $db;
    $id_pemesan = $_POST['id_pemesan'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $alamat = $_POST['alamat'];

    $update_query = "UPDATE pemesan SET nama='$nama', email='$email', nomor_telepon='$nomor_telepon', alamat='$alamat' WHERE id_pemesan='$id_pemesan'";

    mysqli_query($db, $update_query);
    header("Location: admin_pemesan.php"); // Redirect to admin_pemesan.php after update
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
                <h2 class="mt-4">Edit Pemesan</h2>
                <form action="" method="POST" class="row g-3">
                    <div class="col-md-6">
                        <label for="id_pemesan" class="form-label">ID Pemesan</label>
                        <input type="text" class="form-control" id="id_pemesan" name="id_pemesan" value="<?php echo isset($data['id_pemesan']) ? htmlspecialchars($data['id_pemesan']) : ''; ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo isset($data['nama']) ? htmlspecialchars($data['nama']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($data['email']) ? htmlspecialchars($data['email']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon" value="<?php echo isset($data['nomor_telepon']) ? htmlspecialchars($data['nomor_telepon']) : ''; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3"><?php echo isset($data['alamat']) ? htmlspecialchars($data['alamat']) : ''; ?></textarea>
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
