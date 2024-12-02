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
.navbar {
    background-color: red; /* Blue gradient background */
    padding: 15px 20px;
    position: sticky;
    top: 0;
    width: 100%;
    z-index: 1000;
}
.navbar-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
}

.logo {
    font-size: 24px;
    font-weight: bold;
    color: #fff;
    text-decoration: none;
    letter-spacing: 2px;
}

.navbar-links {
    list-style-type: none;
    display: flex;
}

.navbar-links li {
    margin-left: 20px;
}

.navbar-links a {
    text-decoration: none;
    font-size: 18px;
    color: #fff;
    padding: 5px 10px;
    transition: background 0.3s ease, color 0.3s ease;
}

.navbar-links a:hover {
    background-color: #00f2fe;
    color: #333;
    border-radius: 4px;
}



.login-container {
    background-color: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    width: 300px;
}
.login-container {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
  position: relative;
  left:600px;
  margin-top:50px;
   
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
<nav class="navbar">
        <div class="navbar-container">
            <a href="home.php" class="logo">Doctors Asylum</a>
        </div>
    </nav>
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

        <p>Dont have an account? <a href="doctors_register.php">Regsiter</a></p>
    </div>
</body>
</html>
