<?php

$conn = new mysqli('localhost', 'root', '', 'Cafe_Billing_System');

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "";
?>
