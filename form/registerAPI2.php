<?php
require_once 'dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the username from the hidden input or URL parameter
    $username = isset($_POST["username"]) ? $_POST["username"] : (isset($_GET['username']) ? htmlspecialchars($_GET['username']) : null);

    if ($username === null) {
        echo "Username not provided.";
        exit();
    }

    $fname = $_POST["fname"];
    $mname = $_POST["mname"];
    $lname = $_POST["lname"];
    $contact = $_POST["contact"];

    $fullname = $fname . ' ' . $mname . ' ' . $lname;

    // Update additional data in the database (Step 2)
    $sqlUpdate = "UPDATE users SET fullname=?, contactnumber=? WHERE username=?";
    $stmt = $conn->prepare($sqlUpdate);

    if ($stmt) {
        $stmt->bind_param("sss", $fullname, $contact, $username);

        if ($stmt->execute()) {
            echo "New record created successfully. Redirecting to personal info page with username: " . $username;
            sleep($delay = 1);
            header("Location: login.html");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Error preparing update query: " . $conn->error;
    }
}
?>