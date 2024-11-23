<?php
session_start();
include 'connect.php';

// Check if the doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    echo "Please log in first.";
    exit;
}

$doctor_id = $_SESSION['doctor_id'];

// Updated SQL query to join doctor, hospital, and department tables
$sql_doctor = "SELECT * FROM doctors WHERE doctor_id = '$doctor_id'";
$result_doctor = $conn->query($sql_doctor);

if ($result_doctor->num_rows > 0) {
    $doctor = $result_doctor->fetch_assoc();
    $doctor_name = $doctor['name'];
    $doctor_email = $doctor['email'];
    $hospital_id = $doctor['hospital_id'];
    $department_id = $doctor['department_id'];
}

// Fetch hospital name based on hospital_id
$sql_hospital = "SELECT name FROM hospitals WHERE hospital_id = '$hospital_id'";
$result_hospital = $conn->query($sql_hospital);
$hospital_name = "";
if ($result_hospital->num_rows > 0) {
    $hospital_row = $result_hospital->fetch_assoc();
    $hospital_name = $hospital_row['name'];
}

// Fetch department name based on department_id
$sql_department = "SELECT name FROM departments WHERE dept_id = '$department_id'";
$result_department = $conn->query($sql_department);
$department_name = "";
if ($result_department->num_rows > 0) {
    $department_row = $result_department->fetch_assoc();
    $department_name = $department_row['name'];
}

$conn->close();?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor's Panel</title>
    <style>


/* General Styles */
body {
    margin: 0;
    font-family: 'Arial', sans-serif;
    background-color: #f8f9fa;
}

/* Navbar */
.navbar {
    background-color: #2c3e50;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 30px;
    color: #ecf0f1;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.navbar .logo {
    font-size: 24px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
    cursor: pointer;
}

.navbar .logo:hover {
    color: #3498db;
}

/* Header */
.header {
    background-color: #3498db;
    color: white;
    text-align: center;
    padding: 20px 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

.header h1 {
    margin: 0;
    font-size: 32px;
    letter-spacing: 1px;
    font-weight: bold;
    text-transform: uppercase;
}

/* Sidebar and Main Section Styles */
.container {
    display: flex;
    height: calc(100vh - 140px); /* Adjust for navbar + header height */
}

/* Sidebar */
/* Sidebar */
.sidebar {
    width: 25%; /* Sidebar width */
    background-color: #2c3e50;
    color: #ecf0f1;
    padding: 20px;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    height: calc(100vh - 120px); /* Full height minus navbar and header height */
    position: fixed; /* Fix sidebar position */
    top: 140px; /* Adjust for navbar + header combined height */
    left: 0;
    overflow-y: auto; /* Scrollable for overflow content */
}

/* Adjust main-section to account for fixed sidebar */
.main-section {
    margin-left: 25%; /* Push the main section to the right of the sidebar */
    background-color: #ecf0f1;
    padding: 20px;
    overflow-y: auto;
    box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
    height: calc(100vh - 120px); /* Match the height of the sidebar */
    margin-top: 120px; /* Push below the navbar and header */
}

.sidebar h2 {
    font-size: 20px;
    margin-bottom: 15px;
    text-align: center;
    text-transform: uppercase;
    border-bottom: 2px solid #3498db;
    padding-bottom: 10px;
}

.sidebar p {
    font-size: 16px;
    margin-bottom: 10px;
    line-height: 1.5;
    padding: 8px 10px;
    border: 1px solid #34495e;
    border-radius: 5px;
    background-color: #34495e;
}

/* Main Section */

.main-section h1 {
    text-align: center;
    font-size: 28px;
    margin-bottom: 20px;
    color: #2c3e50;
    text-transform: uppercase;
    border-bottom: 2px solid #3498db;
    padding-bottom: 10px;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: white;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

table th, table td {
    padding: 12px;
    text-align: left;
    border: 1px solid #bdc3c7;
}

table th {
    background-color: #3498db;
    color: white;
    font-weight: bold;
    text-transform: uppercase;
}

table tr:nth-child(even) {
    background-color: #f2f2f2;
}

table tr:hover {
    background-color: #dff9fb;
    cursor: pointer;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        flex-direction: column;
    }

    .sidebar {
        width: 100%;
        text-align: center;
        padding: 15px;
    }

    .main-section {
        padding: 15px;
    }

    table th, table td {
        padding: 8px;
    }
}

        /* General Styles */


    </style>
</head>
<body>
     <!-- Navbar -->
     <nav class="navbar">
        <div class="logo">Doctors Asylum</div>
    </nav>

    <!-- Header -->
    <header class="header">
        <h1>Doctors Panel</h1>
    </header>
    <div class="sidebar">
        <h2>Doctor Panel</h2>
        <p><strong>Name:</strong> <?php echo $doctor_name; ?></p>
        <p><strong>Email:</strong> <?php echo $doctor_email; ?></p>
        <p><strong>Hospital:</strong> <?php echo $hospital_name; ?></p>
        <p><strong>Department:</strong> <?php echo $department_name; ?></p>
        <form  method="POST">
            <a href="index.html"><button class="logout" type="submit" name="logout">Logout</button></a>
        </form>
    </div>

    <div class="content">
        <h1>Welcome, Dr. <?php echo $doctor_name; ?>!</h1>
        <!-- Other doctor panel content can go here -->
    </div>
</body>
</html>
