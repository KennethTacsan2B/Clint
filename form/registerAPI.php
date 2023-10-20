<?php
require_once 'dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if the username already exists
    $check_query = "SELECT * FROM users WHERE username=?";
    $stmt_check = $conn->prepare($check_query);

    if ($stmt_check) {
        $stmt_check->bind_param("s", $username);  // Bind the parameter
        $stmt_check->execute();

        if ($stmt_check->get_result()->num_rows > 0) {
            // Username already exists, redirect to the personal info page
            echo "Redirecting to sign in menu: ";
            echo '<script type="text/javascript">alert("Username already exists. Please choose a different username.");</script>';
            sleep($delay = 1);
            header("Location: reguname-I.php?error=Username%20already%20exists.%20Please%20choose%20a%20different%20username.");
            exit();
        }
    } else {
        echo "Error preparing check query: " . $conn->error;
        echo "Redirecting to sign in menu: ";
        echo '<script type="text/javascript">alert("Username already exists. Please choose a different username.");</script>';
            sleep($delay = 1);
            header("Location: reguname-I.php?error=Username%20already%20exists.%20Please%20choose%20a%20different%20username.");
            exit();
    }

    // Username is unique, insert data into the database (Step 1)
    $sqlInsert = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt_insert = $conn->prepare($sqlInsert);

    if ($stmt_insert) {
        $stmt_insert->bind_param("ss", $username, $password);  // Bind the parameters

        if ($stmt_insert->execute()) {
            echo "New record created successfully. Redirecting to personal info page with username: " . $username;
            header("Location: Information.html?username=" . urlencode($username));
            exit();
        } else {
            echo "Error inserting record: " . $conn->error;
        }
    } else {
        echo "Error preparing insert query: " . $conn->error;
    }
}
?>