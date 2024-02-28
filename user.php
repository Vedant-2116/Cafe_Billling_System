<?php
include('connection.php');

// Handle user addition logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // Store password as a string
    $role = $_POST['role'];

    // Validate and insert into the database
    if (!empty($username) && !empty($password) && !empty($role)) {
        $conn->query("INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')");
        header("Location: user.php");
        exit();
    }
}

// Fetch users
$users = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Add any additional CSS styling if needed -->
    <style>
        #addUserForm {
            display: none;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>User List</h2>

        <!-- Add User Button -->
        <button class="btn btn-success" onclick="toggleAddUserForm()">Add User</button>

        <!-- User Addition Form -->
        <form method="post" action="" id="addUserForm">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="role">Role:</label>
                <select id="role" name="role" class="form-control" required>
                    <option value="admin">Admin</option>
                    <option value="employee">Employee</option>
                </select>
            </div>

            <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
        </form>

        <!-- User List -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $user['user_id']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['role'] === 'admin' ? '********' : $user['password']; ?></td>
                        <td><?php echo $user['role']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        function toggleAddUserForm() {
            var form = document.getElementById('addUserForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>

</body>

</html>

