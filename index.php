<?php 
session_start();

$host = 'localhost';
$username = 'efslmbjq_mark';
$password = 'RvdqXJnYgEPUZ27SLcZhs';
$db = 'efslmbjq_mark';

$conn = new mysqli($host, $username, $password, $db);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $sql = "SELECT * FROM users WHERE username = ?";  
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username); 
    $stmt->execute();
    $result = $stmt->get_result();

    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        
        if ($password === $user['password']) { 
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            
            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php"); 
                header("Location: splash2.php");
            } else {
                header("Location: home.php"); 
                header("Location: splash.php");
            }
            exit;
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "Invalid username!";
    }

    $stmt->close();
    $conn->close();
}
?>
