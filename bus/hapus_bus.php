<?php
include '../include/koneksi.php';

hapus_bus();

function hapus_bus() {
    global $db;
    $tnkb = ambil_tnkb();
    $sql = buat_query_hapus($tnkb);

    if (eksekusi_query($sql)) {
        tampilkan_pesan_berhasil();
    } else {
        tampilkan_pesan_error($sql);
    }

    tutup_koneksi();
    alihkan_ke_admin_bus();
}

function ambil_tnkb() {
    return $_GET['tnkb'];
}

function buat_query_hapus($tnkb) {
    return "DELETE FROM bus WHERE tnkb='$tnkb'";
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

function alihkan_ke_admin_bus() {
    header("Location: admin_bus.php");
    exit();
}
?>
