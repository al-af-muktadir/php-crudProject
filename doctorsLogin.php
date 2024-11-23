<?php
// Start the session to store doctor ID after login
session_start();

include('connect.php');
// Handle login form submission
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password']; // The plain text password entered by the user

    // Fetch the doctor's record from the database based on the email
    $sql = "SELECT * FROM doctors WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the doctor's data
        $row = $result->fetch_assoc();

        // Direct password comparison
        if ($password == $row['password']) {
            // Password is correct, start session and redirect to doctor panel
            $_SESSION['doctor_id'] = $row['doctor_id'];
            header("Location: doctorsPanel.php"); // Redirect to doctor panel
            exit();
        } else {
            // Invalid password
            $error_message = "Invalid password!";
        }
    } else {
        // No doctor found with that email
        $error_message = "No doctor found with that email!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Login</title>
    <link rel="stylesheet" href="login.css">
    <style>
        /* Reset some default styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f0f2f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.login-container {
    background-color: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    width: 300px;
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

label {
    font-size: 14px;
    color: #555;
    margin-bottom: 8px;
    display: block;
}

input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

button[type="submit"] {
    width: 100%;
    padding: 12px;
    background-color: #4CAF50;
    color: white;
    font-size: 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button[type="submit"]:hover {
    background-color: #45a049;
}

.error {
    color: red;
    font-size: 14px;
    margin-bottom: 10px;
    text-align: center;
}

    </style>
</head>
<body>
    <div class="login-container">
        <h2>Doctor Login</h2>
        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required><br>

            <?php
            // Display error message if login fails
            if (isset($error_message)) {
                echo "<p class='error'>$error_message</p>";
            }
            ?>

            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>
</html>
