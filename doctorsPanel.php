<?php
session_start();
include 'connect.php';

// Check if the doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    echo "Please log in first.";
    exit;
}

$doctor_id = $_SESSION['doctor_id'];

// Fetch doctor details
$sql_doctor = "SELECT * FROM doctors WHERE doctor_id = '$doctor_id'";
$result_doctor = $conn->query($sql_doctor);

if ($result_doctor->num_rows > 0) {
    $doctor = $result_doctor->fetch_assoc();
    $doctor_name = $doctor['name'];
    $doctor_email = $doctor['email'];
    $hospital_id = $doctor['hospital_id'];
    $department_id = $doctor['department_id'];
}

// Fetch hospital name
$sql_hospital = "SELECT name FROM hospitals WHERE hospital_id = '$hospital_id'";
$result_hospital = $conn->query($sql_hospital);
$hospital_name = $result_hospital->num_rows > 0 ? $result_hospital->fetch_assoc()['name'] : "";

// Fetch department name
$sql_department = "SELECT name FROM departments WHERE dept_id = '$department_id'";
$result_department = $conn->query($sql_department);
$department_name = $result_department->num_rows > 0 ? $result_department->fetch_assoc()['name'] : "";

// Fetch appointments for the logged-in doctor
$sql_appointments = "SELECT appointment_id, patient_name, patient_age, patient_gender, patient_email, 
                            contact_number, appointment_date 
                     FROM appointments 
                     WHERE doctor_id = '$doctor_id'";
$result_appointments = $conn->query($sql_appointments);

$conn->close();
?>

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
        }

        /* Container */
        .container {
            display: flex;
            flex-wrap: nowrap;
            height: calc(100vh - 60px); /* Subtract navbar height */
        }

        /* Sidebar */
        .sidebar {
            width: 25%; /* Sidebar width */
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
            height: 100%; /* Full height */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
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
        }

        .logout {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #e74c3c;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            text-transform: uppercase;
            font-weight: bold;
            text-decoration: none;
            margin-top: 20px;
        }

        .logout:hover {
            background-color: #c0392b;
        }

        /* Main Section */
        .main-section {
            flex: 1; /* Take the remaining space */
            padding: 20px;
            overflow-y: auto;
            background-color: #ecf0f1;
        }

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
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">Doctors Asylum</div>
    </nav>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Doctor Panel</h2>
            <p><strong>Name:</strong> <?php echo $doctor_name; ?></p>
            <p><strong>Email:</strong> <?php echo $doctor_email; ?></p>
            <p><strong>Hospital:</strong> <?php echo $hospital_name; ?></p>
            <p><strong>Department:</strong> <?php echo $department_name; ?></p>
            <a href="index.php" class="logout">Logout</a>
        </div>

        <!-- Main Section -->
        <div class="main-section">
            <h1>Your Appointments</h1>
            <!-- Appointments Table -->
            <?php if ($result_appointments->num_rows > 0) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>Appointment ID</th>
                            <th>Patient Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Appointment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($appointment = $result_appointments->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $appointment['appointment_id']; ?></td>
                                <td><?php echo $appointment['patient_name']; ?></td>
                                <td><?php echo $appointment['patient_age']; ?></td>
                                <td><?php echo $appointment['patient_gender']; ?></td>
                                <td><?php echo $appointment['patient_email']; ?></td>
                                <td><?php echo $appointment['contact_number']; ?></td>
                                <td><?php echo $appointment['appointment_date']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p>No appointments found.</p>
            <?php } ?>
        </div>
    </div>
</body>
</html>
