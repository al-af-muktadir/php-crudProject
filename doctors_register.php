<?php
session_start();
include('connect.php');

if (isset($_POST['submit'])) {
    // Collect form data
    $doctor_name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Save securely using hashing (bcrypt recommended)
    $hospital_name = $_POST['hospital_name'];
    $department_name = $_POST['dept_name'];
    $experience = $_POST['experience'];

    // Check if the hospital exists
    $hospital_query = "SELECT hospital_id FROM hospitals WHERE name = '$hospital_name'";
    $hospital_result = $conn->query($hospital_query);

    if ($hospital_result->num_rows == 0) {
        // If hospital doesn't exist, insert it into the hospital table
        $insert_hospital = "INSERT INTO hospitals (name) VALUES ('$hospital_name')";
        if ($conn->query($insert_hospital)) {
            $hospital_id = $conn->insert_id; // Get the inserted hospital's ID
        } else {
            die("Error inserting hospital: " . $conn->error);
        }
    } else {
        // Get the existing hospital ID
        $hospital_row = $hospital_result->fetch_assoc();
        $hospital_id = $hospital_row['hospital_id'];
    }

    // Check if the department exists for the specific hospital
    $department_query = "SELECT dept_id FROM departments WHERE name = '$department_name' AND hospital_id = '$hospital_id'";
    $department_result = $conn->query($department_query);

    if ($department_result->num_rows == 0) {
        // If department doesn't exist, insert it into the department table
        $insert_department = "INSERT INTO departments (name, hospital_id) VALUES ('$department_name', '$hospital_id')";
        if ($conn->query($insert_department)) {
            $dept_id = $conn->insert_id; // Get the inserted department's ID
        } else {
            die("Error inserting department: " . $conn->error);
        }
    } else {
        // Get the existing department ID
        $department_row = $department_result->fetch_assoc();
        $dept_id = $department_row['dept_id'];
    }

    // Insert doctor into the doctors table
    $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Use bcrypt for password security
    $insert_doctor = "INSERT INTO doctors (name, email, password, hospital_id, department_id, experience) 
                      VALUES ('$doctor_name', '$email', '$hashed_password', '$hospital_id', '$dept_id', '$experience')";
    if ($conn->query($insert_doctor)) {
        // Get the inserted doctor ID
        $doctor_id = $conn->insert_id;

        // Store doctor ID in session for displaying later
        $_SESSION['doctor_id'] = $doctor_id;

        // Redirect to the doctor information page
        header("Location: doctorsPanel.php");
        exit();
    } else {
        die("Error inserting doctor: " . $conn->error);
    }
}

// Close the database connection
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Registration</title>
    <link rel="stylesheet" href="styles.css">
    <style>
     .registration-form {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
  position: relative;
  left:600px;
  margin-top:20px;
   
}

.registration-form {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    width: 300px;
}

.registration-form h2 {
    text-align: center;
    margin-bottom: 20px;
}

.registration-form label {
    display: block;
    margin: 8px 0 4px;
}

.registration-form input {
    width: 100%;
    padding: 8px;
    margin-bottom: 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.registration-form button {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.registration-form button:hover {
    background-color: #45a049;
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
    </style>
</head>
<body>
<nav class="navbar">
        <div class="navbar-container">
            <a href="index.php" class="logo">Doctors Asylum</a>
        </div>
    </nav>
    <div class="registration-form">
        <h2>Doctor Registration</h2>
        <form  method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required><br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required><br>

            <label for="hospital_name">Hospital Name:</label>
            <input type="text" name="hospital_name" id="hospital_name" required><br>

            <label for="dept_name">Department Name:</label>
            <input type="text" name="dept_name" id="dept_name" required><br>
          <label for="dept_name">Experience:</labe>
            <input type="text" name="experience" id="dept_name" required><br>
            <button type="submit" name="submit">Register</button>
        </form>
        <p>Already Have an account ? <a href="doctorsLogin.php">login</a></p>
    </div>
</body>
</html>
