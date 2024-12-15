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

// Fetch all users
$query_users = "SELECT id, username, created_at FROM users ORDER BY created_at DESC";
$result_users = $conn->query($query_users);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>
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
        .user-meta {
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>View Users</h1>
        <div class="button-container">
            <a href="admin_dashboard.php" class="btn">Back to Dashboard</a>
        </div>
        <?php while ($user = $result_users->fetch_assoc()): ?>
            <div class="item">
                <h3><?php echo htmlspecialchars($user['username']); ?></h3>
                <div class="user-meta">
                    <p>Username: <?php echo htmlspecialchars($user['username']); ?></p>
                    <p>Joined on: <?php echo $user['created_at']; ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
<?php
$conn->close();
?>
