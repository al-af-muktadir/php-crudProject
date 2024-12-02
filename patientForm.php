<?php
include 'connect.php';  // Connection to the database
session_start();  // Start session to store patient info

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the registration form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $contact = $_POST['contact'];

    // Hash password for security
   

    // Insert data into patienttable
    $query = "INSERT INTO patients (name, email, password, gender, age, contact) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $name, $email, $password, $gender, $age, $contact);

    if ($stmt->execute()) {
        // Set session for the patient
        $_SESSION['patient_id'] = $stmt->insert_id; // Store patient ID in session
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        header("Location: patientPAnel.php"); // Redirect to patient panel
    } else {
        echo "Error: " . $conn->error;  // In case of error
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors Asylum - Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
  /* Reset some default styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body background and font */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f4f9;
}

/* Navbar Styles */
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

/* Form Container Styles */
.form-container {
    background: #ffffff;
    padding: 30px 40px;
    border-radius: 10px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: 50px auto;
}

.form-container h1 {
    font-size: 28px;
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

.form-container label {
    font-weight: bold;
    margin-bottom: 8px;
    display: block;
    color: #555;
}

.form-container input {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 16px;
    background-color: #f9f9f9;
}

.form-container input:focus {
    border-color: #007BFF;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.form-container input[type="submit"] {
    background-color: #007BFF;
    color: white;
    font-size: 16px;
    font-weight: bold;
    padding: 12px 20px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
    width: 100%;
}

.form-container input[type="submit"]:hover {
    background-color: #0056b3;
}

.form-container p {
    text-align: center;
    color: #555;
}

.form-container a {
    text-decoration: none;
    color: #007BFF;
}

.form-container a:hover {
    text-decoration: underline;
}

</style>
<body>

    <!-- Navbar -->
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Registration</title>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="index.php" class="logo">Doctors Asylum</a>
        </div>
    </nav>

    <!-- Registration Form -->
    <div class="form-container">
        <h1>Register as Patient</h1>
        <form method="POST" action="">
            <label for="name">Name:</label>
            <input type="text" name="name" required><br>

            <label for="email">Email:</label>
            <input type="email" name="email" required><br>

            <label for="password">Password:</label>
            <input type="password" name="password" required><br>

            <label for="age">Age:</label>
            <input type="number" name="age" required><br>

            <label class="px-2 py-4 " for="gender">Gender:</label>
            <select class="px-2 py-2 rounded-2 bg-white border-2"  name="gender" required>
                <option class="px-2 py-2 rounded-4 border-2" value="male">Male</option>
                <option class="px-2 py-2 rounded-4 border-2" value="female">Female</option>
      
            </select><br>

            <label for="contact">Contact:</label>
            <input type="text" name="contact" required><br>

            <input type="submit" value="Register">
        </form>
        <p>Already Have an account ? <a href="PatientLogin.php">login</a></p>
    </div>
</body>
</html>