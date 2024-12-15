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

// Fetch all posts for display
$query_posts = "SELECT id, title, created_at FROM posts ORDER BY created_at DESC";
$result_posts = $conn->query($query_posts);

// Fetch comments for a specific post
$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : null;
if ($post_id) {
    $query_comments = "SELECT comments.id, comments.content, comments.created_at, users.username, comments.parent_id 
                       FROM comments 
                       JOIN users ON comments.user_id = users.id 
                       WHERE comments.post_id = ? 
                       ORDER BY comments.created_at ASC";
    $stmt = $conn->prepare($query_comments);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result_comments = $stmt->get_result();
} else {
    $result_comments = null;
}

// Handle post deletion
if (isset($_GET['delete_post'])) {
    $delete_post_id = (int)$_GET['delete_post'];
    $delete_post_query = "DELETE FROM posts WHERE id = ?";
    $stmt = $conn->prepare($delete_post_query);
    $stmt->bind_param("i", $delete_post_id);
    if ($stmt->execute()) {
        echo "<script>alert('Post deleted successfully'); window.location='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to delete post');</script>";
    }
    $stmt->close();
}

// Handle comment deletion
if (isset($_GET['delete_comment'])) {
    $comment_id = (int)$_GET['delete_comment'];
    $delete_query = "DELETE FROM comments WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $comment_id);
    if ($stmt->execute()) {
        echo "<script>alert('Comment deleted successfully'); window.location='admin_dashboard.php?post_id=$post_id';</script>";
    } else {
        echo "<script>alert('Failed to delete comment');</script>";
    }
    $stmt->close();
}

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_content'], $_POST['parent_id'])) {
    $reply_content = trim($_POST['reply_content']);
    $parent_id = (int)$_POST['parent_id'];
    $user_id = 1; // Admin user ID (update as needed)

    $insert_reply = "INSERT INTO comments (post_id, user_id, content, parent_id, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($insert_reply);
    $stmt->bind_param("iisi", $post_id, $user_id, $reply_content, $parent_id);
    if ($stmt->execute()) {
        echo "<script>alert('Reply added successfully'); window.location='admin_dashboard.php?post_id=$post_id';</script>";
    } else {
        echo "<script>alert('Failed to add reply');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1, h2 {
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            margin: 5px;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .delete-btn {
            background-color: #e53935;
        }
        .delete-btn:hover {
            background-color: #c62828;
        }
        .section {
            margin-bottom: 40px;
        }
        .item {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: left;
        }
        .item h3 {
            margin-bottom: 10px;
        }
        .comment-meta {
            font-size: 14px;
            color: #555;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-group button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .form-group button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <div class="button-container">
            <a href="view_users.php" class="btn">View Users</a>
            <a href="add_post.php" class="btn">Add New Post</a>
            <a href="logout.php" class="btn">Logout</a>
        </div>

        <!-- Blog Posts Management Section -->
        <div class="section">
            <h2>Manage Blog Posts</h2>
            <?php while ($row = $result_posts->fetch_assoc()): ?>
                <div class="item">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p>Created on: <?php echo $row['created_at']; ?></p>
                    <a href="view_comments.php?post_id=<?php echo $row['id']; ?>" class="btn">View Comments</a>
                    <a href="edit_post.php?id=<?php echo $row['id']; ?>" class="btn edit-btn">Edit</a>
                    <a href="?delete_post=<?php echo $row['id']; ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
