<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$result = mysqli_query($koneksi, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f2f5f9;
            margin: 0;
            padding: 0;
        }

        h2, h3 {
            text-align: center;
            color: #2c3e50;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        a {
            text-decoration: none;
            color: #3498db;
            margin: 0 10px;
        }

        a:hover {
            color: #1d6fa5;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .top-bar a {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px 16px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #eef6fb;
        }

        .action-links a {
            margin: 0 5px;
        }

        .action-links a:first-child {
            color: #27ae60;
        }

        .action-links a:last-child {
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Dashboard Admin</h2>

        <div class="top-bar">
            <a href="user_crud.php?action=add">+ Tambah User</a>
            <a href="logout.php">Logout</a>
        </div>

        <h3>Daftar User</h3>

        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['role']; ?></td>
                <td class="action-links">
                    <a href="user_crud.php?action=edit&id=<?php echo $row['id']; ?>">Edit</a>
                    <a href="user_crud.php?action=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Yakin?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
