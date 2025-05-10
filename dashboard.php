<?php
require 'includes/auth.php';
require 'config/db.php';
include 'includes/navbar.php';

// Get current user info
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User'; 

$posts = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$posts->execute([$user_id]);

date_default_timezone_set('Asia/Kolkata'); // or your timezone
$current_datetime = date('l, d M Y h:i A');

// Count total posts
$post_count = $posts->rowCount();
?>

<style>
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f8fafc;
        color: #334155;
        line-height: 1.6;
    }

    .dashboard-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: white;
        border-radius: 12px;
        padding: 25px 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .dashboard-header::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 100%;
        background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.1) 100%);
        transform: skewX(-15deg);
    }

    .dashboard-header h1 {
        margin: 0 0 5px 0;
        font-size: 28px;
        font-weight: 700;
    }

    .dashboard-header h2 {
        margin: 0 0 15px 0;
        font-size: 20px;
        font-weight: 500;
        opacity: 0.9;
    }

    .dashboard-header p {
        margin: 0;
        font-size: 14px;
        opacity: 0.8;
    }

    .stats-container {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }

    .stat-card {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        flex: 1;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    .stat-card h3 {
        margin: 0 0 5px 0;
        font-size: 14px;
        font-weight: 500;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-card p {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
        color: #1e293b;
    }

    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .search-box {
        position: relative;
        flex: 1;
        max-width: 300px;
    }

    .search-box input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
    }

    .search-box input:focus {
        border-color: #3b82f6;
    }

    .search-box::before {
        content: "🔍";
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
    }

    .create-btn {
        background-color: #3b82f6;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 14px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.2s;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .create-btn:hover {
        background-color: #2563eb;
    }

    .create-btn:active {
        transform: translateY(1px);
    }

    .posts-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 20px;
    }

    .post {
        background-color: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .post:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .post-image {
        height: 200px;
        overflow: hidden;
        position: relative;
    }

    .post-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .post:hover .post-image img {
        transform: scale(1.05);
    }

    .post-content {
        padding: 20px;
    }

    .post-content h3 {
        margin: 0 0 10px 0;
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.4;
    }

    .post-content p {
        margin: 0 0 15px 0;
        font-size: 14px;
        color: #64748b;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .post-meta {
        font-size: 12px;
        color: #94a3b8;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .post-meta .dot {
        display: inline-block;
        margin: 0 5px;
        width: 3px;
        height: 3px;
        border-radius: 50%;
        background-color: #cbd5e1;
    }

    .post-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #f1f5f9;
    }

    .post-actions a {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        padding: 6px 12px;
        border-radius: 6px;
        transition: background-color 0.2s;
    }

    .post-actions .edit-btn {
        color: #0369a1;
        background-color: #f0f9ff;
    }

    .post-actions .edit-btn:hover {
        background-color: #e0f2fe;
    }

    .post-actions .delete-btn {
        color: #be123c;
        background-color: #fff1f2;
    }

    .post-actions .delete-btn:hover {
        background-color: #ffe4e6;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background-color: white;
        border-radius: 12px;
        border: 1px dashed #cbd5e1;
    }

    .empty-state h3 {
        margin: 20px 0 10px;
        font-weight: 600;
    }

    .empty-state p {
        color: #64748b;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 20px 15px;
        }
        
        .posts-container {
            grid-template-columns: 1fr;
        }
        
        .stats-container {
            flex-direction: column;
        }
        
        .action-bar {
            flex-direction: column;
            align-items: stretch;
            gap: 15px;
        }
        
        .search-box {
            max-width: none;
        }
    }
</style>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Dashboard</h1>
        <h2>Welcome back, <?= htmlspecialchars($username) ?>!</h2>
        <p><?= $current_datetime ?></p>
    </div>

    <div class="stats-container">
        <div class="stat-card">
            <h3>Total Posts</h3>
            <p><?= $post_count ?></p>
        </div>
        <div class="stat-card">
            <h3>Recent Views</h3>
            <p>--</p>
        </div>
        <div class="stat-card">
            <h3>Comments</h3>
            <p>--</p>
        </div>
    </div>

    <div class="action-bar">
        <div class="search-box">
            <input type="text" placeholder="Search your posts...">
        </div>
        <a href="create_post.php" class="create-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Create New Post
        </a>
    </div>

    <?php if($post_count > 0): ?>
        <div class="posts-container">
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <?php if ($post['image']): ?>
                        <div class="post-image">
                            <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                        </div>
                    <?php endif; ?>
                    <div class="post-content">
                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                        <div class="post-meta">
                            <?= date('M d, Y', strtotime($post['created_at'])) ?>
                            <span class="dot"></span>
                            <?= date('h:i A', strtotime($post['created_at'])) ?>
                        </div>
                        <p><?= nl2br(htmlspecialchars(substr($post['content'], 0, 150) . (strlen($post['content']) > 150 ? '...' : ''))) ?></p>
                        <div class="post-actions">
                            <a href="edit_post.php?id=<?= $post['id'] ?>" class="edit-btn">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M18.5 2.50001C18.8978 2.10219 19.4374 1.87869 20 1.87869C20.5626 1.87869 21.1022 2.10219 21.5 2.50001C21.8978 2.89784 22.1213 3.4374 22.1213 4.00001C22.1213 4.56262 21.8978 5.10219 21.5 5.50001L12 15L8 16L9 12L18.5 2.50001Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Edit
                            </a>
                            <a href="delete_post.php?id=<?= $post['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this post?')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 6H5H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Delete
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19.5 14C19.5 14.8284 18.8284 15.5 18 15.5C17.1716 15.5 16.5 14.8284 16.5 14C16.5 13.1716 17.1716 12.5 18 12.5C18.8284 12.5 19.5 13.1716 19.5 14Z" fill="#94a3b8"/>
                <path d="M7.5 14C7.5 14.8284 6.82843 15.5 6 15.5C5.17157 15.5 4.5 14.8284 4.5 14C4.5 13.1716 5.17157 12.5 6 12.5C6.82843 12.5 7.5 13.1716 7.5 14Z" fill="#94a3b8"/>
                <path d="M10.0681 19.2506C10.1821 19.9467 10.8275 20.5 11.6185 20.5H12.4438C13.2348 20.5 13.8802 19.9467 13.9942 19.2506C14.0331 19.0056 13.8572 18.7765 13.6021 18.7765H10.4602C10.205 18.7765 10.0292 19.0056 10.0681 19.2506Z" fill="#94a3b8"/>
                <path d="M12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12Z" fill="#94a3b8"/>
            </svg>
            <h3>No posts yet</h3>
            <p>Get started by creating your first blog post</p>
            <a href="create_post.php" class="create-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Create New Post
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>