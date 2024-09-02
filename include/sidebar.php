<?php
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Determine the current page for setting the active class in the sidebar
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Dashboard - Bus Rental Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            overflow-x: hidden;
        }
        #sidebar {
            min-width: 250px;
            max-width: 200px;
            transition: all 0.3s;
        }
        #sidebar .p-4  {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        #sidebar .nav-link {
            color: white;
        }
        #sidebar .nav-link:hover {
            background-color: #343a40;
        }
        #sidebar .nav-link.active {
            background-color: #007bff;
        }
        #content {
            transition: margin-left 0.3s;
        }
        .toggled #sidebar {
            margin-left: -250px;
        }
        .toggled #content {
            margin-left: 0;
        }
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
            }
            .toggled #sidebar {
                margin-left: 0;
            }
            #content {
                margin-left: 0;
            }
            .toggled #content {
                margin-left: 250px;
            }
        }
    </style>
</head>
<body>
<nav id="sidebar" class="bg-dark">
    <div class="p-4 pt-5">
    <img src="../img/bus-logo.jpg" alt="Logo" style="max-width: 100px;">
        <h5 class="text-white mt-4">Main Menu</h5>
        <ul class="nav flex-column mb-0">
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'admin_dashboard.php') ? 'active' : ''; ?>" href="../dashboard/admin_dashboard.php">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
        </ul>
        <h5 class="text-white mt-4">Mengelola</h5>
        <ul class="nav flex-column mb-0">
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'admin_bus.php') ? 'active' : ''; ?>" href="../bus/admin_bus.php">
                    <i class="fas fa-bus-alt me-2"></i>Mengelola Bus
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'admin_penyewaan.php') ? 'active' : ''; ?>" href="../penyewaan/admin_penyewaan.php">
                    <i class="fas fa-calendar-check me-2"></i>Mengelola Penyewaan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'admin_pemesan.php') ? 'active' : ''; ?>" href="../pemesan/admin_pemesan.php">
                    <i class="fas fa-users me-2"></i>Mengelola Data Pemesan
                </a>
            </li>
        </ul>
    </div>
</nav>
<!-- Rest of your HTML content -->
</body>
</html>
