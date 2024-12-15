<?php
// Connect to the database
$conn = new mysqli('localhost', 'efslmbjq_mark', 'RvdqXJnYgEPUZ27SLcZh', 'efslmbjq_mark');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize messages
$success_message = $error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image']['name'];

    // Define the directory for file uploads
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);

    if (!empty($image)) {
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = $target_file;
        } else {
            $error_message = "Sorry, there was an error uploading your file.";
        }
    } else {
        $image_path = null; // Allow posts without an image
    }

    // If no errors occurred during file upload
    if (empty($error_message)) {
        // Insert post into the database (assuming `user_id` is set to 1 for admin)
        $stmt = $conn->prepare("INSERT INTO posts (title, content, image, user_id, created_at) VALUES (?, ?, ?, 1, NOW())");
        $stmt->bind_param("sss", $title, $content, $image_path);

        if ($stmt->execute()) {
            $success_message = "New post added successfully.";
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Post</title>
    <style>
        /* General Reset and Body Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }

        /* Container to center content */
        .container {
            max-width: 800px;
            width: 100%;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 24px;
            color: #555;
            margin-bottom: 20px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            text-align: left;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group textarea,
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 10px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        a button {
            margin-top: 20px; 
        }

        .message {
            margin-bottom: 20px;
            font-weight: bold;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 24px;
            }

            h2 {
                font-size: 20px;
            }

            .form-group input[type="text"],
            .form-group textarea,
            .form-group input[type="file"] {
                padding: 8px;
                font-size: 14px;
            }

            button {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Post</h2>

        <!-- Display success or error messages -->
        <?php if (!empty($success_message)): ?>
            <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" required>
            </div>

            <div class="form-group">
                <label for="content">Content:</label>
                <textarea name="content" required></textarea>
            </div>

            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" name="image">
            </div>

            <button type="submit">Add Post</button>
        </form>

        <a href="admin_dashboard.php"><button type="button">Back</button></a>
    </div>
</body>
</html>
