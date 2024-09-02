<?php
session_start();
cek_sesi_dan_role();

include '../include/koneksi.php';

$data = fetch_data_invoice();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['proses'])) {
    proses_form();
}

function cek_sesi_dan_role() {
    if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
        header("Location: ../login.php");
        exit();
    }
}

function fetch_data_invoice() {
    global $db;
    if (isset($_GET['kode'])) {
        $kode = $_GET['kode'];
        $query = mysqli_query($db, "
            SELECT invoice.*, pemesan.nama AS pemesan_nama, bus.merek AS bus_merek 
            FROM invoice 
            LEFT JOIN pemesan ON invoice.id_pemesan = pemesan.id 
            LEFT JOIN bus ON invoice.tnkb = bus.tnkb 
            WHERE invoice.kode = '$kode'
        ");
        $data = mysqli_fetch_array($query);
        if (!$data) {
            // Redirect if no data found
            header("Location: admin_penyewaan.php");
            exit();
        }
        return $data;
    } else {
        // Redirect if no kode is set
        header("Location: admin_penyewaan.php");
        exit();
    }
}

function proses_form() {
    global $db;
    $kode = $_POST['kode'];
    $tglpenyewaan = $_POST['tglpenyewaan'];
    $tglpengembalian = $_POST['tglpengembalian'];
    $tnkb = $_POST['tnkb'];
    $id_pemesan = $_POST['id_pemesan'];
    $totalharga = $_POST['totalharga'];
    $status_pembayaran = $_POST['status_pembayaran'];
    $status_pesanan = $_POST['status_pesanan'];

    // Handle file upload for bukti_pembayaran
    if (isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] == 0) {
        $bukti_pembayaran = addslashes(file_get_contents($_FILES['bukti_pembayaran']['tmp_name']));
        $update_query = "UPDATE invoice SET tglpenyewaan='$tglpenyewaan', tglpengembalian='$tglpengembalian', tnkb='$tnkb', id_pemesan='$id_pemesan', totalharga='$totalharga', status_pembayaran='$status_pembayaran', status_pesanan='$status_pesanan', bukti_pembayaran='$bukti_pembayaran' WHERE kode='$kode'";
    } else {
        $update_query = "UPDATE invoice SET tglpenyewaan='$tglpenyewaan', tglpengembalian='$tglpengembalian', tnkb='$tnkb', id_pemesan='$id_pemesan', totalharga='$totalharga', status_pembayaran='$status_pembayaran', status_pesanan='$status_pesanan' WHERE kode='$kode'";
    }

    mysqli_query($db, $update_query);
    header("Location: admin_penyewaan.php"); // Redirect to admin_penyewaan.php after update
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
                <h2 class="mt-4">Edit Invoice</h2>
                <form action="" method="POST" enctype="multipart/form-data" class="row g-3">
                    <div class="col-md-6">
                        <label for="kode" class="form-label">Kode</label>
                        <input type="text" class="form-control" id="kode" name="kode" value="<?php echo isset($data['kode']) ? htmlspecialchars($data['kode']) : ''; ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="tglpenyewaan" class="form-label">Tanggal Penyewaan</label>
                        <input type="date" class="form-control" id="tglpenyewaan" name="tglpenyewaan" value="<?php echo isset($data['tglpenyewaan']) ? htmlspecialchars($data['tglpenyewaan']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tglpengembalian" class="form-label">Tanggal Pengembalian</label>
                        <input type="date" class="form-control" id="tglpengembalian" name="tglpengembalian" value="<?php echo isset($data['tglpengembalian']) ? htmlspecialchars($data['tglpengembalian']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tnkb" class="form-label">TNKB</label>
                        <input type="text" class="form-control" id="tnkb" name="tnkb" value="<?php echo isset($data['tnkb']) ? htmlspecialchars($data['tnkb']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="id_pemesan" class="form-label">ID Pemesan</label>
                        <input type="number" class="form-control" id="id_pemesan" name="id_pemesan" value="<?php echo isset($data['id_pemesan']) ? htmlspecialchars($data['id_pemesan']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="totalharga" class="form-label">Total Harga</label>
                        <input type="number" class="form-control" id="totalharga" name="totalharga" value="<?php echo isset($data['totalharga']) ? htmlspecialchars($data['totalharga']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="status_pembayaran" class="form-label">Status Pembayaran</label>
                        <select class="form-select" id="status_pembayaran" name="status_pembayaran" required>
                            <option value="pending" <?php if(isset($data['status_pembayaran']) && $data['status_pembayaran'] == 'pending') echo 'selected'; ?>>Pending</option>
                            <option value="paid" <?php if(isset($data['status_pembayaran']) && $data['status_pembayaran'] == 'paid') echo 'selected'; ?>>Paid</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="status_pesanan" class="form-label">Status Pesanan</label>
                        <select class="form-select" id="status_pesanan" name="status_pesanan" required>
                            <option value="belum divalidasi" <?php if(isset($data['status_pesanan']) && $data['status_pesanan'] == 'belum divalidasi') echo 'selected'; ?>>Belum Divalidasi</option>
                            <option value="divalidasi" <?php if(isset($data['status_pesanan']) && $data['status_pesanan'] == 'divalidasi') echo 'selected'; ?>>Divalidasi</option>
                            <option value="dalam proses" <?php if(isset($data['status_pesanan']) && $data['status_pesanan'] == 'dalam proses') echo 'selected'; ?>>Dalam Proses</option>
                            <option value="selesai" <?php if(isset($data['status_pesanan']) && $data['status_pesanan'] == 'selesai') echo 'selected'; ?>>Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran</label>
                        <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*">
                        <?php if (isset($data['bukti_pembayaran']) && $data['bukti_pembayaran']): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($data['bukti_pembayaran']); ?>" alt="Bukti Pembayaran" style="width: 100px; height: auto; margin-top: 10px;">
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
