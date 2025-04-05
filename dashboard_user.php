<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$user_sql = "SELECT * FROM users WHERE username = '$username'";
$user_result = mysqli_query($koneksi, $user_sql);
$user = mysqli_fetch_assoc($user_result);
$user_id = $user['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($koneksi, $_POST['title']);
    $description = mysqli_real_escape_string($koneksi, $_POST['description']);
    $image_path = '';

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $file_name = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        }
    }

    $insert_sql = "INSERT INTO posts (user_id, title, description, image) 
                   VALUES ('$user_id', '$title', '$description', '$image_path')";
    mysqli_query($koneksi, $insert_sql);

    header("Location: dashboard_user.php");
    exit();
}

$post_sql = "SELECT * FROM posts WHERE user_id = '$user_id' ORDER BY created_at DESC";
$post_result = mysqli_query($koneksi, $post_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard User</title>
    <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f5f7fa;
        color: #333;
        margin: 0;
        padding: 0;
        animation: fadeIn 1s ease-in;
    }

    .container {
        width: 90%;
        max-width: 800px;
        margin: 40px auto;
        background-color: #ffffff;
        padding: 30px;
        border-radius: 16px;
        animation: slideIn 0.8s ease-in-out;
        border: 1px solid #e0e0e0;
    }

    h2, h3, h4 {
        color: #1976d2;
    }

    label {
        font-weight: bold;
        color: #222;
    }

    input[type="text"],
    textarea,
    input[type="file"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #fafafa;
        color: #333;
        margin-bottom: 15px;
    }

    input[type="submit"] {
        background-color: #1976d2;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
        background-color: #1565c0;
    }

    .post {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 10px;
        margin-top: 20px;
        background-color: #ffffff;
        animation: fadeInPost 0.5s ease-in-out;
    }

    .post img {
        max-width: 100%;
        border-radius: 10px;
        margin-top: 10px;
    }

    .post small {
        color: #888;
    }

    .post a {
        text-decoration: none;
        color: #1976d2;
        margin-right: 10px;
    }

    .post a:hover {
        text-decoration: underline;
    }

    .logout {
        display: inline-block;
        margin-top: 20px;
        color: #d32f2f;
        text-decoration: none;
    }

    .logout:hover {
        text-decoration: underline;
    }

    @keyframes fadeIn {
        from {opacity: 0;}
        to {opacity: 1;}
    }

    @keyframes slideIn {
        from {transform: translateY(30px); opacity: 0;}
        to {transform: translateY(0); opacity: 1;}
    }

    @keyframes fadeInPost {
        from {opacity: 0; transform: translateY(10px);}
        to {opacity: 1; transform: translateY(0);}
    }
</style>



</head>
<body>
    <div class="container">
        <h2>Selamat Datang, <?php echo htmlspecialchars($username); ?></h2>

        <h3>Buat Postingan Baru</h3>
        <form method="post" enctype="multipart/form-data">
            <label>Judul:</label>
            <input type="text" name="title" required>

            <label>Deskripsi:</label>
            <textarea name="description" rows="5" required></textarea>

            <label>Upload Gambar:</label>
            <input type="file" name="image">

            <input type="submit" value="Posting">
        </form>
        </div>


    <div class="container">
        <h3>Postingan Kamu</h3>
        <?php while ($post = mysqli_fetch_assoc($post_result)): ?>
            <div class="post">
                <h4><?php echo htmlspecialchars($post['title']); ?></h4>
                <p><?php echo nl2br(htmlspecialchars($post['description'])); ?></p>
                <?php if (!empty($post['image'])): ?>
                    <img src="<?php echo $post['image']; ?>">
                <?php endif; ?>
                <small>Diposting pada: <?php echo $post['created_at']; ?></small><br><br>
                <a href="edit_post.php?id=<?php echo $post['id']; ?>">Edit</a>
                <a href="delete_post.php?id=<?php echo $post['id']; ?>" onclick="return confirm('Yakin hapus postingan ini?');">Hapus</a>
            </div>
        <?php endwhile; ?>

        <p><a class="logout" href="logout.php">Logout</a></p>
    </div>
</body>
</html>
