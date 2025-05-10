<?php include 'includes/navbar.php'; ?>
<?php
require 'config/db.php';

$limit = 5;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Get posts with author usernames
$stmt = $pdo->prepare("
    SELECT posts.*, users.username 
    FROM posts 
    JOIN users ON posts.user_id = users.id 
    ORDER BY created_at DESC 
    LIMIT ? OFFSET ?
");
$stmt->bindValue(1, $limit, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();

// Count total posts for pagination
$countStmt = $pdo->query("SELECT COUNT(*) FROM posts");
$totalPosts = $countStmt->fetchColumn();
$totalPages = ceil($totalPosts / $limit);
?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f0f2f5;
    }

    .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
    }

    h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
    }

    .post {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.04);
        overflow: hidden;
    }

    .post h2 {
        margin: 0 0 10px;
        font-size: 20px;
        color: #222;
    }

    .post small {
        color: #666;
        display: block;
        margin-bottom: 10px;
    }

    .post img {
        width: 100%;
        max-height: 250px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 12px;
    }

    .post p {
        font-size: 15px;
        line-height: 1.6;
        color: #444;
        margin: 0;
    }

    .pagination {
        text-align: center;
        margin-top: 30px;
    }

    .pagination a {
        display: inline-block;
        margin: 0 5px;
        padding: 8px 14px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-size: 14px;
        transition: background 0.2s ease;
    }

    .pagination a:hover:not(.disabled) {
        background-color: #0056b3;
    }

    .pagination a.disabled {
        background-color: #ccc;
        pointer-events: none;
        color: #777;
    }
</style>

<div class="container">
    <h1>Latest Blog Posts</h1>

    <?php foreach ($posts as $post): ?>
        <div class="post">
            <h2><?= htmlspecialchars($post['title']) ?></h2>
            <small>By <?= htmlspecialchars($post['username']) ?> | <?= date('d M Y, h:i A', strtotime($post['created_at'])) ?></small>
            <?php if ($post['image']): ?>
                <img src="<?= htmlspecialchars($post['image']) ?>" alt="Post image">
            <?php endif; ?>
            <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
        </div>
    <?php endforeach; ?>

    <div class="pagination">
        <a href="?page=<?= $page - 1 ?>" class="<?= $page <= 1 ? 'disabled' : '' ?>">Prev</a>
        <a href="?page=<?= $page + 1 ?>" class="<?= $page >= $totalPages ? 'disabled' : '' ?>">Next</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
