<?php include 'includes/navbar.php'; ?>
<?php
require 'includes/auth.php';
require 'config/db.php';

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);

header("Location: dashboard.php");
?>
<?php include 'includes/footer.php'; ?>
