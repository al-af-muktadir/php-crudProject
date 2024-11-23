<?php
session_start();
include('connect.php');
if (isset($_POST['submit'])) {
    // Collect form data
    $doctor_name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // The plain text password
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
            $error_message = "Error inserting hospital!";
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
            $error_message = "Error inserting department!";
        }
    } else {
        // Get the existing department ID
        $department_row = $department_result->fetch_assoc();
        $dept_id = $department_row['dept_id'];
    }

    // Insert doctor into the doctors table
    $insert_doctor = "INSERT INTO doctors (name, email, password, hospital_id, department_id, experience) 
                      VALUES ('$doctor_name', '$email', '$password', '$hospital_id', '$dept_id', '$experience')";
    if ($conn->query($insert_doctor)) {
        // Get the inserted doctor ID
        $doctor_id = $conn->insert_id;

        // Insert doctor_id into the department table
        $update_department = "UPDATE departments SET doctor_id = '$doctor_id' WHERE dept_id = '$dept_id'";
        if ($conn->query($update_department)) {
            header("Location: doctorsPanel.php"); // Redirect to the doctor panel
            exit();
        } else {
            $error_message = "Error linking doctor to department!";
        }
    } else {
        $error_message = "Error inserting doctor!";
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
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
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

    </style>
</head>
<body>
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
    </div>
</body>
</html>
