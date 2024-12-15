<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['comment_id']) && isset($_GET['post_id'])) {
    $comment_id = $_GET['comment_id'];
    $post_id = $_GET['post_id'];

    $conn = new mysqli('localhost', 'efslmbjq_mark', 'RvdqXJnYgEPUZ27SLcZh', 'efslmbjq_mark');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch the comment owner
    $comment_query = "SELECT user_id FROM comments WHERE id = $comment_id";
    $comment_result = $conn->query($comment_query);
    if ($comment_result->num_rows == 0) {
        header("Location: view_post.php?post_id=$post_id");
        exit;
    }

    $comment = $comment_result->fetch_assoc();

    // Check if the logged-in user is the comment owner or an admin
    if ($_SESSION['role'] === 'admin' || $_SESSION['user'] === $comment['user']) {
        $delete_comment_query = "DELETE FROM comments WHERE id = $comment_id";
        if ($conn->query($delete_comment_query) === TRUE) {
            header("Location: view_post.php?post_id=$post_id");
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "You do not have permission to delete this comment.";
    }

    $conn->close();
} else {
    header("Location: index.php");
    exit;
}
?>
