<?php
include '../include/koneksi.php';

hapus_pemesan();

function hapus_pemesan() {
    global $db;
    $id_pemesan = ambil_id_pemesan();
    $sql = buat_query_hapus($id_pemesan);

    if (eksekusi_query($sql)) {
        tampilkan_pesan_berhasil();
    } else {
        tampilkan_pesan_error($sql);
    }

    tutup_koneksi();
    alihkan_ke_admin_pemesan();
}

function ambil_id_pemesan() {
    return $_GET['id_pemesan'];
}

function buat_query_hapus($id_pemesan) {
    return "DELETE FROM pemesan WHERE id_pemesan='$id_pemesan'";
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

function alihkan_ke_admin_pemesan() {
    header("Location: admin_pemesan.php");
    exit();
}
?>
