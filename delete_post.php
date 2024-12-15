<?php 
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$conn = new mysqli('localhost', 'efslmbjq_mark', 'RvdqXJnYgEPUZ27SLcZh', 'efslmbjq_mark');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // First, delete related comments
    $delete_comments_query = "DELETE FROM comments WHERE post_id = $post_id";
    if (!$conn->query($delete_comments_query)) {
        echo "Error deleting comments: " . $conn->error;
        exit;
    }

    // Now delete the post
    $query = "DELETE FROM posts WHERE id = $post_id";
    if ($conn->query($query)) {
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Error deleting post: " . $conn->error;
    }
}
?>
