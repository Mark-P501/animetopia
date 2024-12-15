<?php
$error_message = "";
if (isset($_GET['error']) && $_GET['error'] == 1) {
    $error_message = "Invalid username or password";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        height: 100vh;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        
        }
        
        .login-container {
        background-color: rgb(200, 190, 238);
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        text-align: center;
        }
        
        h2 {
        margin-bottom: 20px;
        color: #333;
        }
        .error-message {
            color: red; /* Error message in red */
            margin-bottom: 20px;
            font-weight: bold;
        }
        
        label {
        display: block;
        text-align: left;
        margin-bottom: 8px;
        color: #555;
        font-weight: bold;
        }
        
        input[type="text"], input[type="password"] {
        width: 100%;
        padding: 5px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        }
        
        button {
        width: 100%;
        padding: 12px;
        background-color: #3d0acad2;
        border: none;
        color: white;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        }
        
        button:hover {
        background-color: #0056b3;
        }
        
        body {
        padding: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>LOGIN</h2>
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
        <form action="index.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button><br>          
        </form>
    </div>
</body>
</html>