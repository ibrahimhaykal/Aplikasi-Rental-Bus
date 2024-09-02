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
    $tglpenyewaan = $_POST['tglpenyewaan'];
    $tglpengembalian = $_POST['tglpengembalian'];
    $tnkb = $_POST['tnkb'];
    $id_pemesan = $_POST['id_pemesan'];
    $status_pembayaran = $_POST['status_pembayaran'];
    $status_pesanan = $_POST['status_pesanan'];

    $harga_sewa = get_harga_sewa($tnkb);
    $totalharga = hitung_total_harga($tglpenyewaan, $tglpengembalian, $harga_sewa);

    $bukti_pembayaran = upload_bukti_pembayaran();

    if ($bukti_pembayaran) {
        simpan_data_invoice($tglpenyewaan, $tglpengembalian, $tnkb, $id_pemesan, $totalharga, $status_pembayaran, $status_pesanan, $bukti_pembayaran);
    } else {
        echo "Error uploading file.";
    }
}

function get_harga_sewa($tnkb) {
    global $db;
    $query = $db->prepare("SELECT harga FROM bus WHERE tnkb = ?");
    $query->bind_param("s", $tnkb);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();
    return $row['harga'];
}

function hitung_total_harga($tglpenyewaan, $tglpengembalian, $harga_sewa) {
    $tgl_penyewaan = new DateTime($tglpenyewaan);
    $tgl_pengembalian = new DateTime($tglpengembalian);
    $selisih_hari = $tgl_penyewaan->diff($tgl_pengembalian)->days + 1; // termasuk hari penyewaan dan pengembalian
    return $selisih_hari * $harga_sewa;
}

function upload_bukti_pembayaran() {
    $upload_dir = '../uploads/bukti_pembayaran/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['bukti_pembayaran']['tmp_name'];
        $file_name = basename($_FILES['bukti_pembayaran']['name']);
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($file_tmp, $file_path)) {
            return $file_path;
        }
    }
    return NULL;
}

function simpan_data_invoice($tglpenyewaan, $tglpengembalian, $tnkb, $id_pemesan, $totalharga, $status_pembayaran, $status_pesanan, $bukti_pembayaran) {
    global $db;
    $sql = "INSERT INTO invoice (tglpenyewaan, tglpengembalian, tnkb, id_pemesan, totalharga, status_pembayaran, status_pesanan, bukti_pembayaran) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sssiisss", $tglpenyewaan, $tglpengembalian, $tnkb, $id_pemesan, $totalharga, $status_pembayaran, $status_pesanan, $bukti_pembayaran);

    if ($stmt->execute()) {
        header("Location: admin_penyewaan.php");
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
    <title>Dashboard - Invoice Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../include/navbar.php'?>
    <div class="d-flex toggled" id="wrapper">
        <?php include '../include/sidebar.php' ?>
        <div id="content" class="container-fluid">
            <main class="p-4">
            <h2 class="mt-4">Tambah Invoice</h2>
    <form action="" method="POST" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-6">
            <label for="tglpenyewaan" class="form-label">Tanggal Penyewaan</label>
            <input type="date" class="form-control" id="tglpenyewaan" name="tglpenyewaan" required>
        </div>
        <div class="col-md-6">
            <label for="tglpengembalian" class="form-label">Tanggal Pengembalian</label>
            <input type="date" class="form-control" id="tglpengembalian" name="tglpengembalian" required>
        </div>
        <div class="col-md-6">
            <label for="tnkb" class="form-label">TNKB</label>
            <select class="form-select" id="tnkb" name="tnkb" required>
                <option value="">--Pilih--</option>
                <?php
                $query = mysqli_query($db, "SELECT * FROM bus");
                while ($data = mysqli_fetch_array($query)) {
                  echo "<option value='".$data['tnkb']."' data-harga='".$data['harga']."'>".$data['merek']."</option>";
                }
                ?>
              </select>
        </div>
        <div class="col-md-6">
            <label for="id_pemesan" class="form-label">ID Pemesan</label>
            <select class="form-select" id="id_pemesan" name="id_pemesan" required>
                <option value="">--Pilih--</option>
                <?php
                $query = mysqli_query($db, "SELECT * FROM pemesan");
                while ($data = mysqli_fetch_array($query)) {
                  echo "<option value='".$data['id_pemesan']."'>".$data['nama']."</option>";
                }
                ?>
              </select>
        </div>
        <div class="col-md-6">
            <label for="totalharga" class="form-label">Total Harga</label>
            <input type="number" class="form-control" id="totalharga" name="totalharga" required readonly>
        </div>
        <div class="col-md-6">
            <label for="status_pembayaran" class="form-label">Status Pembayaran</label>
            <select class="form-select" id="status_pembayaran" name="status_pembayaran" required>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="status_pesanan" class="form-label">Status Pesanan</label>
            <select class="form-select" id="status_pesanan" name="status_pesanan" required>
                <option value="">Belum Divalidasi</option>
                <option value="">Sudah Divalidasi</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran</label>
            <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*" required>
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

        document.getElementById('tglpenyewaan').addEventListener('change', calculateTotalPrice);
        document.getElementById('tglpengembalian').addEventListener('change', calculateTotalPrice);
        document.getElementById('tnkb').addEventListener('change', calculateTotalPrice);

        function calculateTotalPrice() {
            var tglpenyewaan = document.getElementById('tglpenyewaan').value;
            var tglpengembalian = document.getElementById('tglpengembalian').value;
            var tnkbSelect = document.getElementById('tnkb');
            var harga = tnkbSelect.options[tnkbSelect.selectedIndex].getAttribute('data-harga');

            if (tglpenyewaan && tglpengembalian && harga) {
                var tglPenyewaan = new Date(tglpenyewaan);
                var tglPengembalian = new Date(tglpengembalian);
                var selisihHari = Math.ceil((tglPengembalian - tglPenyewaan) / (1000 * 60 * 60 * 24)) + 1; // termasuk hari penyewaan dan pengembalian
                var totalHarga = selisihHari * harga;
                document.getElementById('totalharga').value = totalHarga;
            }
        }
    </script>
</body>
</html>
