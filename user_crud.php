<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    mysqli_query($koneksi, "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')");
    header("Location: dashboard_admin.php");
} elseif ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    mysqli_query($koneksi, "UPDATE users SET username='$username', password='$password', role='$role' WHERE id=$id");
    header("Location: dashboard_admin.php");
} elseif ($action === 'delete') {
    mysqli_query($koneksi, "DELETE FROM users WHERE id=$id");
    header("Location: dashboard_admin.php");
}

if ($action === 'edit' || $action === 'add') {
    $user = ['username' => '', 'password' => '', 'role' => 'user'];
    if ($action === 'edit') {
        $res = mysqli_query($koneksi, "SELECT * FROM users WHERE id=$id");
        $user = mysqli_fetch_assoc($res);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo ucfirst($action); ?> User</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e6f2ff;
            color: #333;
            padding: 40px;
        }

        h2 {
            text-align: center;
            color: #0077cc;
        }

        form {
            background-color: #ffffff;
            padding: 30px;
            max-width: 400px;
            margin: 20px auto;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #0077cc;
            color: white;
            padding: 10px;
            margin-top: 20px;
            width: 100%;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #005fa3;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #0077cc;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2><?php echo ucfirst($action); ?> User</h2>
    <form method="post">
        <label>Username:</label>
        <input type="text" name="username" value="<?php echo $user['username']; ?>" required>

        <label>Password:</label>
        <input type="password" name="password" value="<?php echo $user['password']; ?>" required>

        <label>Role:</label>
        <select name="role">
            <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
            <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
        </select>

        <input type="submit" value="Simpan">
    </form>
    <a href="dashboard_admin.php">Kembali</a>
</body>
</html>
<?php } ?>
