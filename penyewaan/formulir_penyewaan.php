<?php
session_start();
include '../include/koneksi.php';

function proses_form() {
    global $db;

    if (!isset($_SESSION['user_id'])) {
        die('User ID is not set in the session. Please log in again.');
    }

    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $alamat = $_POST['alamat'];
    $tglpenyewaan = $_POST['tglpenyewaan'];
    $tglpengembalian = $_POST['tglpengembalian'];
    $tnkbFull = $_POST['tnkb'];
    $tnkb = explode('-', $tnkbFull)[0]; // Extract TNKB part only
    $user_id = $_SESSION['user_id'];

    // Fetch daily rate from bus table
    $stmt = $db->prepare("SELECT harga FROM bus WHERE tnkb = ?");
    $stmt->bind_param("s", $tnkb);
    $stmt->execute();
    $stmt->bind_result($harga);
    $stmt->fetch();
    $stmt->close();

    if ($harga === null) {
        die('Harga not found for the given TNKB.');
    }

    // Calculate total price
    $tglPenyewaan = new DateTime($tglpenyewaan);
    $tglPengembalian = new DateTime($tglpengembalian);
    $selisihHari = $tglPenyewaan->diff($tglPengembalian)->days + 1; // including rental and return day
    $totalharga = $selisihHari * $harga;

    // Insert invoice data
    $stmt = $db->prepare("INSERT INTO invoice (tglpenyewaan, tglpengembalian, tnkb, user_id, totalharga, status_pembayaran, status_pesanan) VALUES (?, ?, ?, ?, ?, 'pending', 'pending')");
    $stmt->bind_param("sssii", $tglpenyewaan, $tglpengembalian, $tnkb, $user_id, $totalharga);
    $stmt->execute();
    $invoice_id = $stmt->insert_id;
    $stmt->close();

    // Insert pemesan data
    $stmt = $db->prepare("INSERT INTO pemesan (nama, email, nomor_telepon, alamat, kode_invoice) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $nama, $email, $nomor_telepon, $alamat, $invoice_id);
    $stmt->execute();
    $stmt->close();

    $db->close();

    header("Location: status_penyewaan_user.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    proses_form();
}

// Fetch the TNKB value from the query parameter if present
$tnkb = isset($_GET['tnkb']) ? $_GET['tnkb'] : '';

// Fetch TNKB, Jenis, and Merek from the database
$tnkbData = '';
if ($tnkb) {
    $stmt = $db->prepare("SELECT tnkb, jenis, merek FROM bus WHERE tnkb = ?");
    $stmt->bind_param("s", $tnkb);
    $stmt->execute();
    $stmt->bind_result($tnkbValue, $jenis, $merek);
    $stmt->fetch();
    if ($tnkbValue && $jenis && $merek) {
        $tnkbData = "$tnkbValue-$jenis-$merek";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Formulir Penyewaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 800px;
            margin: auto;
            margin-left: 90px;
        }
        .form-container .row {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php include '../include/navbar.php'?>
    
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <?php include '../include/sidebar_user.php' ?>
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <h3 class="mt-5 text-center">Formulir Penyewaan</h3>
                <div class="form-container">
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control" id="nomor_telepon" name="nomor_telepon" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" required></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tglpenyewaan" class="form-label">Tanggal Penyewaan</label>
                                <input type="date" class="form-control" id="tglpenyewaan" name="tglpenyewaan" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tglpengembalian" class="form-label">Tanggal Pengembalian</label>
                                <input type="date" class="form-control" id="tglpengembalian" name="tglpengembalian" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tnkb" class="form-label">Informasi Bus Yang Dipilih</label>
                                <input type="text" class="form-control" id="tnkb" name="tnkb" value="<?php echo htmlspecialchars($tnkbData); ?>" required readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="totalharga" class="form-label">Total Harga</label>
                                <input type="number" class="form-control" id="totalharga" name="totalharga" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <p>Informasi Pembayaran: Silakan melakukan pembayaran via transfer ke rekening BCA 1234567890.</p>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.getElementById('wrapper').classList.toggle('toggled');
    });

    document.addEventListener('DOMContentLoaded', function() {
    const tglPenyewaanInput = document.getElementById('tglpenyewaan');
    const tglPengembalianInput = document.getElementById('tglpengembalian');
    const tnkbInput = document.getElementById('tnkb');
    const totalHargaInput = document.getElementById('totalharga');

    const calculateTotalHarga = () => {
        const tglPenyewaan = tglPenyewaanInput.value;
        const tglPengembalian = tglPengembalianInput.value;
        const tnkbFull = tnkbInput.value;
        const tnkb = tnkbFull.split('-')[0].trim(); // Extract and trim TNKB part only
        if (tglPenyewaan && tglPengembalian && tnkb) {
            fetch(`get_harga.php?tnkb=${encodeURIComponent(tnkb)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.harga) {
                        const hargaPerHari = data.harga;
                        const tanggalPenyewaan = new Date(tglPenyewaan);
                        const tanggalPengembalian = new Date(tglPengembalian);
                        const diffTime = Math.abs(tanggalPengembalian - tanggalPenyewaan);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                        const totalHarga = hargaPerHari * diffDays;
                        totalHargaInput.value = totalHarga;
                    } else {
                        alert('Error fetching harga: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error fetching harga:', error);
                    alert('Error fetching harga. Please try again later.');
                });
        }
    };

    // Initial calculation on page load if dates and TNKB are pre-filled
    calculateTotalHarga();

    tglPenyewaanInput.addEventListener('change', calculateTotalHarga);
    tglPengembalianInput.addEventListener('change', calculateTotalHarga);
});
    </script>
</body>
</html>
