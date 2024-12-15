<?php  
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$conn = new mysqli('localhost', 'efslmbjq_mark', 'RvdqXJnYgEPUZ27SLcZh', 'efslmbjq_mark');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$post_id = $_GET['post_id'];  // Get the post ID from the URL

// Fetch the post details
$query = "SELECT posts.id, posts.title, posts.content, posts.image, posts.created_at, users.username AS author 
          FROM posts 
          JOIN users ON posts.user_id = users.id 
          WHERE posts.id = $post_id";
$result = $conn->query($query);
$post = $result->fetch_assoc();

// Fetch comments for the post
$comments_query = "
    SELECT comments.id, comments.content, comments.created_at, comments.user_id, users.username
    FROM comments
    JOIN users ON comments.user_id = users.id
    WHERE comments.post_id = $post_id
    ORDER BY comments.created_at ASC";
$comments_result = $conn->query($comments_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
        }
        .header h1 {
            margin: 0;
        }
        .logout-btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout-btn:hover {
            background-color: lightskyblue;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
        }
        .post {
            background-color: white;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .post-header {
            display: flex;
            align-items: center;
            padding: 15px;
        }
        .post-header img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }
        .post-header .author {
            font-weight: bold;
            color: #333;
        }
        .post-header .time {
            font-size: 12px;
            color: #777;
        }
        .post-content {
            padding: 15px;
        }
        .post img {
            width: 100%;
            height: auto;
        }
        .post h2 {
            margin: 10px 0;
            font-size: 18px;
        }
        .post p {
            color: #555;
            line-height: 1.6;
        }
        .comment-section {
            margin-top: 20px;
        }
        .comment {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .comment .commenter {
            font-weight: bold;
            color: #333;
        }
        .comment .time {
            font-size: 12px;
            color: #777;
        }
        .comment-form {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }
        .comment-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .comment-form button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .comment-form button:hover {
            background-color: lightblue;
        }
    
        .back-button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .back-button:hover {
            background-color: lightblue;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>ANIMETOPIA</h1>
        <form method="POST" action="logout.php">
            <button class="logout-btn" type="submit">Logout</button>
        </form>
    </div>

    <div class="container">
        <div class="post">
            <div class="post-header">
                <img src="path/to/default-avatar.png" alt="Author Avatar"> <!-- Placeholder for user avatar -->
                <div>
                    <span class="author"><?php echo htmlspecialchars($post['author']); ?></span><br>
                    <span class="time"><?php echo $post['created_at']; ?></span>
                </div>
            </div>
            <div class="post-content">
                <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                <?php if ($post['image']): ?>
                    <img src="<?php echo $post['image']; ?>" alt="Post Image">
                <?php endif; ?>
                <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
            </div>
        </div>

        <div class="comment-section">
            <h3>Comments</h3>
            <?php while ($comment = $comments_result->fetch_assoc()): ?>
                <div class="comment">
                    <div class="commenter"><?php echo htmlspecialchars($comment['username']); ?></div>
                    <div class="time"><?php echo $comment['created_at']; ?></div>
                    <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                </div>
            <?php endwhile; ?>
            
            <form class="comment-form" method="POST" action="add_comment.php">
                <textarea name="content" placeholder="Add a comment..." required></textarea>
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                <button type="submit">Submit</button>
            </form>
            <a href="home.php" class="back-button">Back to Home</a>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>
























