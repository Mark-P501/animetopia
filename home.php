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

// Fetch all blog posts
$query = "SELECT posts.id, posts.title, posts.content, posts.image, posts.created_at, users.username AS author 
          FROM posts 
          JOIN users ON posts.user_id = users.id 
          ORDER BY posts.created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Homepage</title>
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
            border: 1px;
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

        .read-more-btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }

        .read-more-btn:hover {
            background-color: lightskyblue;
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
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="post">
                <div class="post-header">
                    <img src="path/to/default-avatar.png" alt="Author Avatar"> <!-- Placeholder for user avatar -->
                    <div>
                        <span class="author"><?php echo htmlspecialchars($row['author']); ?></span><br>
                        <span class="time"><?php echo $row['created_at']; ?></span>
                    </div>
                </div>
                <div class="post-content">
                    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                    <?php if ($row['image']): ?>
                        <img src="<?php echo $row['image']; ?>" alt="Post Image">
                    <?php endif; ?>
                    <p><?php echo nl2br(htmlspecialchars(substr($row['content'], 0, 300))); ?>...</p> <!-- Show only part of the content -->

                    <a href="view_post.php?post_id=<?php echo $row['id']; ?>" class="read-more-btn">Read More</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    

    
    <script>
        // Scroll preservation for the homepage
        if (sessionStorage.getItem('scrollPosition')) {
            window.scrollTo(0, sessionStorage.getItem('scrollPosition'));
        }

        window.onbeforeunload = function() {
            sessionStorage.setItem('scrollPosition', window.scrollY);
        };
    </script>

</body>
</html>
