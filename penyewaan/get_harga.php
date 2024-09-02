<?php
include '../include/koneksi.php';

$tnkb = $_GET['tnkb'];
$response = [];

if ($tnkb) {
    $stmt = $db->prepare("SELECT harga FROM bus WHERE tnkb = ?");
    $stmt->bind_param("s", $tnkb);
    $stmt->execute();
    $stmt->bind_result($harga);
    if ($stmt->fetch()) {
        $response['harga'] = $harga;
    } else {
        $response['error'] = 'TNKB not found';
    }
    $stmt->close();
} else {
    $response['error'] = 'Invalid TNKB';
}

header('Content-Type: application/json');
echo json_encode($response);
$db->close();
?>
