<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.html');
    exit;
}

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "kantin_bahagia";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Get transaction statistics
$total_sales = 0;
$total_transactions = 0;
$recent_transactions = [];

$result = $conn->query("SELECT COUNT(*) as count, SUM(total) as total FROM transactions");
if ($result) {
    $row = $result->fetch_assoc();
    $total_transactions = $row['count'] ?? 0;
    $total_sales = $row['total'] ?? 0;
}

$result = $conn->query("SELECT * FROM transactions ORDER BY created_at DESC LIMIT 10");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recent_transactions[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Kantin Bahagia</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
    .dashboard-container {
        max-width: 1200px;
        margin: 100px auto 50px;
        padding: 0 20px;
    }

    .dashboard-header {
        background: linear-gradient(135deg, var(--primary), var(--primary3));
        color: white;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 0.8rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .stat-card i {
        font-size: 2.5rem;
        color: var(--primary);
        margin-bottom: 1rem;
    }

    .stat-card h3 {
        font-size: 2rem;
        color: var(--primary2);
        margin: 0.5rem 0;
    }

    .transactions-table {
        background: white;
        border-radius: 0.8rem;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    th {
        background-color: var(--bgc);
        font-weight: 600;
    }

    .logout-btn {
        background: white;
        color: var(--primary);
        padding: 0.5rem 1.5rem;
        border-radius: 2rem;
        text-decoration: none;
        font-weight: 600;
    }

    .logout-btn:hover {
        background-color: var(--primary);
        color: white;
    }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="index.html" class="navbar-logo">Kantin<span>Bahagia</span></a>
        <div class="navbar-nav">
            <a href="index.html">Kembali ke Home</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <div>
                <h1><i data-feather="user-check"></i> Admin Dashboard</h1>
                <p>Halo, <?php echo $_SESSION['admin_username']; ?>!</p>
            </div>
            <div class="admin-badge active">
                <i data-feather="shield"></i>
                <span>Admin Mode</span>
            </div>
        </div>

        <div class="stats-cards">
            <div class="stat-card">
                <i data-feather="dollar-sign"></i>
                <h3>Rp<?php echo number_format($total_sales, 0, ',', '.'); ?></h3>
                <p>Total Penjualan</p>
            </div>

            <div class="stat-card">
                <i data-feather="shopping-cart"></i>
                <h3><?php echo $total_transactions; ?></h3>
                <p>Total Transaksi</p>
            </div>

            <div class="stat-card">
                <i data-feather="users"></i>
                <h3>1</h3>
                <p>Admin Aktif</p>
            </div>
        </div>

        <div class="transactions-table">
            <h3 style="padding: 1.5rem; margin: 0;">Transaksi Terbaru</h3>
            <?php if (!empty($recent_transactions)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Admin</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                        <th>Items</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_transactions as $transaction): ?>
                    <tr>
                        <td>#<?php echo $transaction['id']; ?></td>
                        <td><?php echo $transaction['admin_username'] ?? '-'; ?></td>
                        <td>Rp<?php echo number_format($transaction['total'], 0, ',', '.'); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($transaction['created_at'])); ?></td>
                        <td><?php echo count(json_decode($transaction['items'], true)); ?> item</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p style="text-align: center; padding: 2rem;">Belum ada transaksi</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
    feather.replace();
    </script>
</body>

</html>