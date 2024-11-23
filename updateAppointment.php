<?php
include 'connect.php';  // Database connection

// Fetch hospital names and doctor names for the dropdown
$hospitals_query = "SELECT * FROM hospitals";
$hospitals_result = $conn->query($hospitals_query);

$doctors_query = "SELECT * FROM doctors";
$doctors_result = $conn->query($doctors_query);

// Get the appointment details by ID
if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];
    $appointment_query = "SELECT a.*, d.name AS doctor_name, h.name AS hospital_name, p.name AS patient_name, dept.name AS department_name
                           FROM appointments a
                           JOIN doctors d ON a.doctor_id = d.doctor_id
                           JOIN hospitals h ON a.hospital_id = h.hospital_id
                           JOIN patients p ON a.patient_id = p.patient_id
                           JOIN departments dept ON a.dept_id = dept.dept_id
                           WHERE a.appointment_id = ?";
    $stmt = $conn->prepare($appointment_query);
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $appointment = $result->fetch_assoc();
}

// Handle the form submission for updating appointment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_name = $_POST['patient_name'];
    $patient_age = $_POST['patient_age'];
    $appointment_date = $_POST['appointment_date'];

    // Update query for the appointment
    $update_query = "UPDATE appointments
                     SET patient_name = ?, patient_age = ?, appointment_date = ?
                     WHERE appointment_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sssi", $patient_name, $patient_age, $appointment_date, $appointment_id);

    // Execute the update query
    if ($update_stmt->execute()) {
        header("Location: viewAppointmentTable.php");  // Redirect to view all appointments after updating
        exit();  // Ensure script stops after redirect
    } else {
        echo "Error updating appointment: " . $update_stmt->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Appointment</title>
    <!-- Include the CSS -->
    <style>
       <style>
    /* Main container for the page */
    .main-container {
        width: 80%;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Heading style */
    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    /* Form container */
    .form-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
    }

    /* Form input fields and labels */
    .input-group {
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 600px;
    }

    .input-group label {
        font-weight: bold;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .input-field {
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 15px;
    }

    .input-field:disabled {
        background-color: #f7f7f7;
    }

    /* Update button style */
    .btn-update {
        padding: 10px 20px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-update:hover {
        background-color: #218838;
    }

    /* Cancel button style */
    .btn-cancel {
        padding: 10px 20px;
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-cancel:hover {
        background-color: #c82333;
    }

    /* Flex container for the buttons */
    .button-container {
        display: flex;
        justify-content: space-between;
        gap: 20px;
    }
</style>

    </style>
</head>
<body>
    <div class="main-container">
        <h2>Update Appointment</h2>

        <!-- Form Container -->
        <form method="POST" class="form-container">
            
            <!-- Patient Name Input -->
            <div class="input-group">
                <label for="patient_name">Patient Name:</label>
                <input type="text" name="patient_name" id="patient_name" class="input-field" value="<?php echo $appointment['patient_name']; ?>" required>
            </div>

            <!-- Patient Age Input -->
            <div class="input-group">
                <label for="patient_age">Patient Age:</label>
                <input type="number" name="patient_age" id="patient_age" class="input-field" value="<?php echo $appointment['patient_age']; ?>" required>
            </div>

            <!-- Appointment Date Input -->
            <div class="input-group">
                <label for="appointment_date">Appointment Date:</label>
                <input type="date" name="appointment_date" id="appointment_date" class="input-field" value="<?php echo $appointment['appointment_date']; ?>" required>
            </div>

            <!-- Doctor Name (Disabled) -->
            <div class="input-group">
                <label for="doctor_name">Doctor Name:</label>
                <input type="text" name="doctor_name" id="doctor_name" class="input-field" value="<?php echo $appointment['doctor_name']; ?>" disabled>
            </div>

            <!-- Hospital Name (Disabled) -->
            <div class="input-group">
                <label for="hospital_name">Hospital Name:</label>
                <input type="text" name="hospital_name" id="hospital_name" class="input-field" value="<?php echo $appointment['hospital_name']; ?>" disabled>
            </div>

            <!-- Department Name (Disabled) -->
            <div class="input-group">
                <label for="department_name">Department Name:</label>
                <input type="text" name="department_name" id="department_name" class="input-field" value="<?php echo $appointment['department_name']; ?>" disabled>
            </div>

            <!-- Button Container -->
            <div class="button-container">
                <button type="submit" class="btn-update">Update</button>
                <a href="viewAppointmentTable.php" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
