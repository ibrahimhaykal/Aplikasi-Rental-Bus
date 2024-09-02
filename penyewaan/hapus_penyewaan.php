<?php
include '../include/koneksi.php';

hapus_invoice();

function hapus_invoice() {
    global $db;
    $kode = ambil_kode();
    $sql = buat_query_hapus($kode);

    if (eksekusi_query($sql)) {
        tampilkan_pesan_berhasil();
    } else {
        tampilkan_pesan_error($sql);
    }

    tutup_koneksi();
    alihkan_ke_admin_penyewaan();
}

function ambil_kode() {
    return $_GET['kode'];
}

function buat_query_hapus($kode) {
    return "DELETE FROM invoice WHERE kode='$kode'";
}

function eksekusi_query($sql) {
    global $db;
    return $db->query($sql) === TRUE;
}

function tampilkan_pesan_berhasil() {
    echo "Data berhasil dihapus";
}

function tampilkan_pesan_error($sql) {
    global $db;
    echo "Error: " . $sql . "<br>" . $db->error;
}

function tutup_koneksi() {
    global $db;
    $db->close();
}

function alihkan_ke_admin_penyewaan() {
    header("Location: admin_penyewaan.php");
    exit();
}
?>
