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

$post_query = mysqli_query($koneksi, "SELECT * FROM posts WHERE id = '$post_id' AND user_id = '$user_id'");
$post = mysqli_fetch_assoc($post_query);

if (!$post) {
    die("Postingan tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($koneksi, $_POST['title']);
    $description = mysqli_real_escape_string($koneksi, $_POST['description']);

    $image_path = $post['image'];

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $file_name = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        }
    }

    $update_sql = "UPDATE posts SET title='$title', description='$description', image='$image_path' WHERE id='$post_id'";
    mysqli_query($koneksi, $update_sql);
    header("Location: dashboard_user.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Postingan</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 16px;
            border: 1px solid #e0e0e0;
        }

        h2 {
            text-align: center;
            color: #1976d2;
            margin-bottom: 30px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fafafa;
        }

        input[type="submit"] {
            background-color: #1976d2;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #1565c0;
        }

        img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Postingan</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Judul:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>

        <label>Deskripsi:</label>
        <textarea name="description" rows="5" required><?php echo htmlspecialchars($post['description']); ?></textarea>

        <label>Gambar Saat Ini:</label>
        <?php if (!empty($post['image'])): ?>
            <img src="<?php echo $post['image']; ?>" alt="Gambar Saat Ini">
        <?php else: ?>
            <p>Tidak ada gambar</p>
        <?php endif; ?>

        <label>Ganti Gambar (opsional):</label>
        <input type="file" name="image">

        <input type="submit" value="Simpan Perubahan">
    </form>
</div>

</body>
</html>
