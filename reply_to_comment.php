<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $content = $_POST['content'];
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    $conn = new mysqli('localhost', 'efslmbjq_mark', 'RvdqXJnYgEPUZ27SLcZh', 'efslmbjq_mark');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO comments (post_id, content, user_id, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("isi", $post_id, $content, $user_id);

    if ($stmt->execute()) {
        header("Location: view_post.php?post_id=$post_id");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
