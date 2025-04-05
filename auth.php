<?php
session_start();
include 'koneksi.php';

function showMessage($message, $backLink = 'login.php') {
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <title>Login/Register</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #e6f2ff;
                text-align: center;
                padding: 100px;
            }

            .message-box {
                background-color: #ffffff;
                padding: 30px;
                border-radius: 12px;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
                max-width: 400px;
                margin: 0 auto;
            }

            .message-box p {
                font-size: 18px;
                color: #333;
                margin-bottom: 20px;
            }

            .btn {
                display: inline-block;
                background-color: #0077cc;
                color: #fff;
                padding: 10px 20px;
                border: none;
                border-radius: 8px;
                text-decoration: none;
                font-weight: bold;
                transition: background-color 0.3s ease;
            }

            .btn:hover {
                background-color: #005fa3;
            }
        </style>
    </head>
    <body>
        <div class='message-box'>
            <p>$message</p>
            <a class='btn' href='$backLink'>Coba Login Lagi</a>
        </div>
    </body>
    </html>
    ";
    exit();
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($koneksi, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: dashboard_admin.php");
        } else {
            header("Location: dashboard_user.php");
        }
    } else {
        showMessage("Login gagal. Cek username atau password.");
    }
} elseif (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $check = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($check) > 0) {
        showMessage("Username sudah terdaftar.");
    } else {
        $insert = mysqli_query($koneksi, "INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'user')");
        if ($insert) {
            showMessage("Registrasi berhasil. <a class='btn' href='login.php'>Login Sekarang</a>", "#");
        } else {
            showMessage("Registrasi gagal.");
        }
    }
}
?>
