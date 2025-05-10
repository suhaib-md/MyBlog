<?php include 'includes/navbar.php'; ?>
<?php 
require 'includes/auth.php'; 
require 'config/db.php';

$post_id = $_GET['id'] ?? null;
if (!$post_id) {
    echo "Invalid post ID.";
    exit;
}

// Fetch post to verify ownership
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    echo "Post not found.";
    exit;
}

if ($post['user_id'] != $_SESSION['user_id']) {
    echo "Unauthorized access.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $image = $post['image']; // Keep existing image unless replaced
    
    if (!empty($_FILES['image']['name'])) {
        $uniqueName = uniqid() . '_' . basename($_FILES['image']['name']);
        $target = "uploads/" . $uniqueName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = $target;
        }
    }
    
    $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, image = ? WHERE id = ?");
    $stmt->execute([$title, $content, $image, $post_id]);
    header("Location: dashboard.php");
    exit;
}
?>

<style>
    body {
        font-family: 'Inter', Arial, sans-serif;
        background-color: #f8fafc;
        padding: 20px;
        color: #334155;
    }

    .form-container {
        max-width: 700px;
        margin: 30px auto;
        background-color: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #1e293b;
        font-weight: 600;
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        color: #475569;
    }

    input[type="text"],
    textarea {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 16px;
        transition: border-color 0.2s;
    }

    input[type="text"]:focus,
    textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }

    textarea {
        min-height: 180px;
        resize: vertical;
    }

    .current-image {
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 8px;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .current-image p {
        margin-top: 0;
        margin-bottom: 10px;
        font-weight: 500;
        color: #475569;
    }

    .current-image img {
        max-width: 100%;
        height: auto;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .file-input-container {
        margin-bottom: 20px;
    }

    .file-input-label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        color: #475569;
    }

    input[type="file"] {
        width: 100%;
        padding: 8px;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .file-tip {
        display: block;
        font-size: 13px;
        color: #64748b;
        margin-top: 5px;
    }

    .buttons-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
    }

    button[type="submit"] {
        background-color: #3b82f6;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
        flex: 0 0 auto;
    }

    button[type="submit"]:hover {
        background-color: #2563eb;
    }

    .back-link {
        display: inline-block;
        padding: 10px 16px;
        border-radius: 6px;
        text-decoration: none;
        color: #4b5563;
        font-weight: 500;
        font-size: 15px;
        background-color: #f1f5f9;
        transition: background-color 0.2s;
    }

    .back-link:hover {
        background-color: #e2e8f0;
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 20px;
        }
        
        .buttons-container {
            flex-direction: column-reverse;
            gap: 15px;
        }
        
        button[type="submit"] {
            width: 100%;
        }
        
        .back-link {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="form-container">
    <h2>Edit Post</h2>
    <form method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required placeholder="Enter post title" value="<?= htmlspecialchars($post['title']) ?>">

        <label for="content">Content:</label>
        <textarea name="content" id="content" rows="8" required placeholder="Write your post content here..."><?= htmlspecialchars($post['content']) ?></textarea>

        <?php if ($post['image']): ?>
            <div class="current-image">
                <p>Current Image:</p>
                <img src="<?= htmlspecialchars($post['image']) ?>" alt="Current post image">
            </div>
        <?php endif; ?>

        <div class="file-input-container">
            <label for="image" class="file-input-label">Change Image (optional):</label>
            <input type="file" name="image" id="image">
            <span class="file-tip">Leave empty to keep current image</span>
        </div>

        <div class="buttons-container">
            <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
            <button type="submit">Update Post</button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>