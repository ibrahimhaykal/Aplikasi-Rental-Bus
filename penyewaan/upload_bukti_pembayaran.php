<?php
session_start();

// Check if the user is logged in and has the role 'user'
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit();
}

// Database connection
include '../include/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode = $_POST['kode'];
    $user_id = $_SESSION['user_id'];

    // Directory where the file will be saved
    $target_dir = "../uploads/bukti_pembayaran/";
    $target_file = $target_dir . basename($_FILES["bukti_pembayaran"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is a actual image or fake image
    $check = getimagesize($_FILES["bukti_pembayaran"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["bukti_pembayaran"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["bukti_pembayaran"]["tmp_name"], $target_file)) {
            // Update the database with the path to the uploaded file
            $sql = $db->prepare("UPDATE invoice SET bukti_pembayaran = ?, status_pembayaran = 'waiting' WHERE kode = ? AND user_id = ?");
            $sql->bind_param("ssi", $target_file, $kode, $user_id);
            if ($sql->execute()) {
                header("Location: status_penyewaan_user.php");
                exit();
            } else {
                echo "Sorry, there was an error updating your record.";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
