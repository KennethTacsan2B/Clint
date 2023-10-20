<?php
require_once 'dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $username = $_GET["username"];
    $password = $_GET["password"];  // In a real application, you should hash the password for security

    if (empty($username) || empty($password)) {
        echo "Username and password are required.";
    } else {
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $username, $password);  // Bind the parameters

            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows === 1) {
                    // Login successful
                    header("Location: search.html");
                exit();
                } else {
                    echo "Invalid username or password.";
                }
            } else {
                echo "Error executing query: " . $conn->error;
            }
        } else {
            echo "Error preparing query: " . $conn->error;
        }

        $stmt->close();
    }
}
?>
