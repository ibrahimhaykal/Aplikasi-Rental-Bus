<?php
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit();
}

// Determine the current page for setting the active class in the sidebar
$current_page = basename($_SERVER['PHP_SELF']);
?>
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
<nav id="sidebar" class="bg-dark">
    <div class="p-4 pt-5">
    <img src="../img/bus-logo.jpg" alt="Logo" style="max-width: 100px;">
        <h5 class="text-white mt-4">Main Menu</h5>
        <ul class="nav flex-column mb-0">
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'user_dashboard.php') ? 'active' : ''; ?>" href="../dashboard/user_dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
            </li>
        </ul>
        <h5 class="text-white mt-4">Penyewaan</h5>
        <ul class="nav flex-column mb-0">
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'user_bus.php') ? 'active' : ''; ?>" href="../bus/user_bus.php"><i class="fas fa-bus-alt me-2"></i>Daftar Bus Pariwisata</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'status_penyewaan_user.php') ? 'active' : ''; ?>" href="../penyewaan/status_penyewaan_user.php"><i class="fas fa-calendar-check me-2"></i>Status Penyewaan</a>
            </li>
        </ul>
    </div>
</nav>
