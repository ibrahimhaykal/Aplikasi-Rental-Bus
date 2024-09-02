<?php
session_start();

// Check if the user is logged in and has the role 'user'
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit();
}

// Database connection
include '../include/koneksi.php';

// Get invoice data
$kode = $_GET['kode'];
$user_id = $_SESSION['user_id'];

// Fetch invoice details
$sql_invoice = $db->prepare("SELECT * FROM invoice WHERE kode = ? AND user_id = ?");
$sql_invoice->bind_param("si", $kode, $user_id);
$sql_invoice->execute();
$result_invoice = $sql_invoice->get_result();

if ($result_invoice->num_rows > 0) {
    $invoice = $result_invoice->fetch_assoc();
} else {
    echo "No invoice found.";
    exit();
}

// Fetch bus details
$sql_bus = $db->prepare("SELECT * FROM bus WHERE tnkb = ?");
$sql_bus->bind_param("s", $invoice['tnkb']);
$sql_bus->execute();
$result_bus = $sql_bus->get_result();
$bus = $result_bus->fetch_assoc();

// Fetch user details
$sql_user = $db->prepare("SELECT * FROM pemesan WHERE kode_invoice = ?");
$sql_user->bind_param("i", $invoice['kode']);
$sql_user->execute();
$result_user = $sql_user->get_result();
$pemesan = $result_user->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Cetak Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td{
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
    </style>
</head>
<body onclick="window.print()">
<div class="invoice-box">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title">
                            <h1>Invoice</h1>
                        </td>
                        
                        <td>
                            Invoice #: <?= htmlspecialchars($invoice['kode']) ?><br>
                            Created: <?= htmlspecialchars($invoice['tglpenyewaan']) ?><br>
                            Due: <?= htmlspecialchars($invoice['tglpengembalian']) ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr class="information">
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            <?= htmlspecialchars($pemesan['nama']) ?><br>
                            <?= htmlspecialchars($pemesan['alamat']) ?><br>
                            <?= htmlspecialchars($pemesan['nomor_telepon']) ?>
                        </td>
                        
                        <td>
                            <?= htmlspecialchars($bus['merek']) ?> <?= htmlspecialchars($bus['jenis']) ?><br>
                            TNKB: <?= htmlspecialchars($invoice['tnkb']) ?><br>
                            Capacity: <?= htmlspecialchars($bus['kapasitas']) ?><br>
                            Facilities: <?= htmlspecialchars($bus['fasilitas']) ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr class="heading">
            <td>
                Payment Method
            </td>
            
            <td>
                Status
            </td>
        </tr>
        
        <tr class="details">
            <td>
                <?= htmlspecialchars($invoice['status_pembayaran']) ?>
            </td>
            
            <td>
                <?= htmlspecialchars($invoice['status_pesanan']) ?>
            </td>
        </tr>
        
        <tr class="heading">
            <td>
                Description
            </td>
            
            <td>
                Price
            </td>
        </tr>
        
        <tr class="item">
            <td>
                Bus Rental
            </td>
            
            <td>
                <?= htmlspecialchars($invoice['totalharga']) ?>
            </td>
        </tr>
        
        <tr class="total">
            <td></td>
            
            <td>
               Total: <?= htmlspecialchars($invoice['totalharga']) ?>
            </td>
        </tr>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
