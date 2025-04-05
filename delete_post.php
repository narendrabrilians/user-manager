<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$post_id = $_GET['id'] ?? null;
if (!$post_id) {
    die("ID tidak ditemukan.");
}

$username = $_SESSION['username'];
$user_query = mysqli_query($koneksi, "SELECT id FROM users WHERE username = '$username'");
$user = mysqli_fetch_assoc($user_query);
$user_id = $user['id'];

// Hapus hanya jika milik user
$check = mysqli_query($koneksi, "SELECT image FROM posts WHERE id='$post_id' AND user_id='$user_id'");
$post = mysqli_fetch_assoc($check);

if ($post) {
    // Hapus gambar dari folder
    if (!empty($post['image']) && file_exists($post['image'])) {
        unlink($post['image']);
    }

    mysqli_query($koneksi, "DELETE FROM posts WHERE id='$post_id'");
}

header("Location: dashboard_user.php");
exit();
?>