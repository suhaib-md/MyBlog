<?php include 'includes/navbar.php'; ?>
<?php
require 'includes/auth.php';
require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $user_id = $_SESSION['user_id'];

    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $uniqueName = uniqid() . '_' . basename($_FILES['image']['name']);
        $target = "uploads/" . $uniqueName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = $target;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, content, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $title, $content, $image]);
    header("Location: dashboard.php");
    exit;
}
?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f0f2f5;
    }

    .form-container {
        max-width: 600px;
        margin: 30px auto;
        background-color: white;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    input[type="text"],
    textarea,
    input[type="file"] {
        width: 100%;
        padding: 10px;
        margin: 10px 0 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    button[type="submit"] {
        background-color: #007bff;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        width: 100%;
    }

    button[type="submit"]:hover {
        background-color: #0056b3;
    }

    .back-link {
        display: block;
        text-align: center;
        margin-top: 15px;
        text-decoration: none;
        color: #007bff;
    }

    .back-link:hover {
        text-decoration: underline;
    }
</style>

<div class="form-container">
    <h2>Create New Post</h2>
    <form method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required placeholder="Enter post title">

        <label for="content">Content:</label>
        <textarea name="content" id="content" rows="6" required placeholder="Write your post content here..."></textarea>

        <label for="image">Upload Image:</label>
        <input type="file" name="image" id="image">

        <button type="submit">Create Post</button>
    </form>
    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
</div>

<?php include 'includes/footer.php'; ?>
