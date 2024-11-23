<?php
include 'connect.php';  // Database connection
session_start();  // Start session to get patient info

// Check if patient is logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: patientLogin.php");  // Redirect to login page if not logged in
    exit();
}

$patient_id = $_SESSION['patient_id'];

// Fetch patient details
$query = "SELECT * FROM patients WHERE patient_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patient_id); // Bind the patient ID
$stmt->execute();
$patient_result = $stmt->get_result();
$patient = $patient_result->fetch_assoc();

// Fetch appointments for the logged-in patient
$appointments_query = "SELECT 
                            a.appointment_date, 
                         
                            d.name AS doctor_name, 
                            h.name AS hospital_name, 
                            dp.name AS department_name 
                        FROM appointments a
                        JOIN doctors d ON a.doctor_id = d.doctor_id
                        JOIN hospitals h ON a.hospital_id = h.hospital_id
                        JOIN departments dp ON a.dept_id = dp.dept_id
                        WHERE a.patient_id = ?";
$appointments_stmt = $conn->prepare($appointments_query);
$appointments_stmt->bind_param("i", $patient_id);
$appointments_stmt->execute();
$appointments_result = $appointments_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Panel</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Add styles for table and layout */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .navbar {
            background-color: #007BFF;
            padding: 15px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
        }

        .main-container {
            display: flex;
            padding: 20px;
        }

        .sidebar {
            width: 300px;
            background: #fff;
            padding: 20px;
            margin-right: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .main-section {
            flex: 1;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        table th {
            background-color: #007BFF;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="home.php" class="logo">Doctors Asylum</a>
        <div class="navbar-links">
            <a href="bookingForm.php" class="nav-link">Book Appointment</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h3>Patient Information</h3>
            <ul>
                <li><strong>Name:</strong> <?php echo $patient['name']; ?></li>
                <li><strong>Email:</strong> <?php echo $patient['email']; ?></li>
                <li><strong>Age:</strong> <?php echo $patient['age']; ?></li>
                <li><strong>Gender:</strong> <?php echo $patient['gender']; ?></li>
                <li><strong>Contact:</strong> <?php echo $patient['contact']; ?></li>
            </ul>
            <button id="appointments-btn" onclick="showAppointments()">My Appointments</button>
        </div>

        <!-- Main Section -->
        <div class="main-section" id="main-section">
            <h2>Appointments</h2>
            <?php if ($appointments_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Doctor Name</th>
                            <th>Hospital Name</th>
                            <th>Department</th>
                            <th>Appointment Date</th>
                         
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($appointment = $appointments_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $appointment['doctor_name']; ?></td>
                                <td><?php echo $appointment['hospital_name']; ?></td>
                                <td><?php echo $appointment['department_name']; ?></td>
                                <td><?php echo $appointment['appointment_date']; ?></td>
                                
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No appointments booked yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Function to toggle the visibility of the appointments section
        function showAppointments() {
            var mainSection = document.getElementById('main-section');
            mainSection.style.display = mainSection.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>
