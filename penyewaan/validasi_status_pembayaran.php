<?php
session_start();
include '../include/koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['kode'])) {
    $kode = $_GET['kode'];

    // Update status pembayaran
    $sql = "UPDATE invoice SET status_pembayaran='Pembayaran Berhasil' WHERE kode=?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $kode);

    if ($stmt->execute()) {
        // Get TNKB for updating bus status
        $sql = "SELECT tnkb FROM invoice WHERE kode=?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $kode);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $tnkb = $row['tnkb'];

        // Update bus status to 'tidak tersedia'
        $sql = "UPDATE bus SET status='Tidak Tersedia' WHERE tnkb=?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $tnkb);
        $stmt->execute();

        header("Location: admin_penyewaan.php");
    } else {
        echo "Error updating record: " . $db->error;
    }
    $stmt->close();
}

$db->close();
?>
