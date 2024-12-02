<?php
session_start();
include('connect.php');

// Check if the form is submitted
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize input and check if patient exists
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check if patient exists
    $query = "SELECT * FROM patients WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $patient = mysqli_fetch_assoc($result);

    if ($patient && password_verify($password, $patient['password'])) {
        // Password matches, set session and redirect to patient panel
        $_SESSION['patient_id'] = $patient['patient_id'];
        $_SESSION['patient_name'] = $patient['name'];
        header("Location: PatientPAnel.php");
        exit();
    } else {
        $error_message = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .login-form {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .login-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .login-form button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-form button:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            text-align: center;
        }
        .navbar {
    background-color: #4facfe; /* Blue gradient background */
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

    </style>
</head>
<body>
<nav class="navbar">
        <div class="navbar-container">
            <a href="index.php" class="logo">Doctors Asylum</a>
            <a href="index.php"><button class="btn">Home</button></a>
        </div>
    </nav>
    <div class="login-form">
        <h2>Patient Login</h2>

        <?php if (isset($error_message)) { ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php } ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" name="login">Login</button>
        </form>
        <p>Dont have an account? <a href="patientForm.php">Please Register </a></p>
    </div>

</body>
</html>
