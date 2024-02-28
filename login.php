<?php
// Include the database connection file
include 'connection.php';

// Start the session
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect based on user role
    if ($_SESSION['role'] == 'admin') {
        header('Location: admin.php');
    } elseif ($_SESSION['role'] == 'employee') {
        header('Location: take.php');
    }
    exit();
}

// Initialize variables
$username = '';
$password = '';
$loginError = '';

// Handle login logic if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL query to check if the username exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password
        if ($password == $row['password']) {
            // Password is correct, set session
            session_start();
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            // Redirect based on user role
            if ($row['role'] == 'admin') {
                header('Location: admin.php');
            } elseif ($row['role'] == 'employee') {
                header('Location: take.php');
            }
            exit();
        } else {
            $loginError = 'Invalid username or password';
        }
    } else {
        $loginError = 'No User Found';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background-image: url('cafe.jpg'); 
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        form {
            text-align: center;
            margin-right: 30px;
            margin-bottom: -130px;
        }

        input {
            margin-bottom: 20px;
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
        }

        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .error-message {
            color: red;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <form method="post" action="">
        <input type="text" name="username" placeholder="Username" required value="<?php echo htmlspecialchars($username); ?>">
        <br>
        <input type="password" name="password" placeholder="Password" required>
        <br>
        <button type="submit">Login</button>
        <div class="error-message"><?php echo $loginError; ?></div>
    </form>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
