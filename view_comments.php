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

// Fetch the post ID from the query string
if (!isset($_GET['post_id'])) {
    die("Post ID is missing.");
}
$post_id = (int)$_GET['post_id'];

// Fetch comments for the post
$query_comments = "SELECT comments.id, comments.content, comments.created_at, users.username
                   FROM comments 
                   JOIN users ON comments.user_id = users.id 
                   WHERE comments.post_id = ? 
                   ORDER BY comments.created_at ASC";
$stmt = $conn->prepare($query_comments);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result_comments = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Comments</title>
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
        h1 {
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
        <h1>Comments for Post ID: <?php echo $post_id; ?></h1>
        <div class="button-container">
            <a href="admin_dashboard.php" class="btn">Back to Dashboard</a>
        </div>
        <?php while ($comment = $result_comments->fetch_assoc()): ?>
            <div class="item">
                <p><?php echo htmlspecialchars($comment['content']); ?></p>
                <div class="comment-meta">
                    <strong>By:</strong> <?php echo htmlspecialchars($comment['username']); ?>
                    | <strong>Created at:</strong> <?php echo $comment['created_at']; ?>
                </div>
                <a href="view_comments.php?post_id=<?php echo $post_id; ?>&delete_comment=<?php echo $comment['id']; ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this comment?');">Delete</a>
                <form method="POST" style="margin-top: 10px;">
                    <input type="hidden" name="parent_id" value="<?php echo $comment['id']; ?>">
                    <textarea name="reply_content" placeholder="Write your reply here..." required></textarea><br>
                    <button type="submit" class="btn">Reply</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
