<?php
include 'connect.php';  // Database connection

// Fetch hospital names, doctor names, and patient IDs for the search bar
$hospitals_query = "SELECT * FROM hospitals";
$hospitals_result = $conn->query($hospitals_query);

$doctors_query = "SELECT * FROM doctors";
$doctors_result = $conn->query($doctors_query);

// Initialize search filters
$search_query = "";
$hospital_id = isset($_POST['hospital_id']) ? $_POST['hospital_id'] : '';
$doctor_id = isset($_POST['doctor_id']) ? $_POST['doctor_id'] : '';
$patient_id = isset($_POST['patient_id']) ? $_POST['patient_id'] : '';

// Build the search query
if ($hospital_id || $doctor_id || $patient_id) {
    $search_query = "WHERE 1=1";  // Start with a base condition for the query

    if ($hospital_id) {
        $search_query .= " AND a.hospital_id = $hospital_id";
    }
    if ($doctor_id) {
        $search_query .= " AND a.doctor_id = $doctor_id";
    }
    if ($patient_id) {
        $search_query .= " AND a.patient_id = $patient_id";
    }
}

// Fetch appointments with the search filter applied and include department
$appointments_query = "SELECT a.*, d.name AS doctor_name, h.name AS hospital_name, a.patient_name, dept.name AS department_name
                       FROM appointments a
                       JOIN doctors d ON a.doctor_id = d.doctor_id
                       JOIN hospitals h ON a.hospital_id = h.hospital_id
                       JOIN departments dept ON a.dept_id = dept.dept_id
                       $search_query";  // Apply the search filter here

$appointments_result = $conn->query($appointments_query);

// Handle the deletion of an appointment
if (isset($_GET['delete'])) {
    $appointment_id = $_GET['delete'];
    $delete_query = "DELETE FROM appointments WHERE appointment_id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $appointment_id);
    
    if ($delete_stmt->execute()) {
        header("Location: viewAppointmentTable.php");  // Redirect after deletion
    } else {
        echo "Error deleting appointment: " . $delete_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments</title>

    <style>
        .main-container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .search-form {
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
        }

        .search-form label {
            font-weight: bold;
            margin-right: 10px;
            margin-bottom: 5px;
            flex: 1;
        }

        .input-field {
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            flex: 2;
        }

        .btn-search {
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-search:hover {
            background-color: #0056b3;
        }

        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .appointments-table th, .appointments-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .appointments-table th {
            background-color: #f4f4f4;
        }

        .btn-update, .btn-delete {
            padding: 5px 10px;
            margin-right: 10px;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-update {
            background-color: #28a745;
            color: white;
        }

        .btn-update:hover {
            background-color: #218838;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <h2>Manage Appointments</h2>

        <!-- Search Form -->
        <form method="POST" class="search-form">
            <label for="hospital_id">Hospital:</label>
            <select name="hospital_id" id="hospital_id" class="input-field">
                <option value="">Select Hospital</option>
                <?php while ($hospital = $hospitals_result->fetch_assoc()): ?>
                    <option value="<?php echo $hospital['hospital_id']; ?>" <?php echo ($hospital['hospital_id'] == $hospital_id) ? 'selected' : ''; ?>>
                        <?php echo $hospital['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="doctor_id">Doctor:</label>
            <select name="doctor_id" id="doctor_id" class="input-field">
                <option value="">Select Doctor</option>
                <?php while ($doctor = $doctors_result->fetch_assoc()): ?>
                    <option value="<?php echo $doctor['doctor_id']; ?>" <?php echo ($doctor['doctor_id'] == $doctor_id) ? 'selected' : ''; ?>>
                        <?php echo $doctor['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="patient_id">Patient ID:</label>
            <input type="text" name="patient_id" id="patient_id" class="input-field" value="<?php echo $patient_id; ?>">

            <button type="submit" class="btn-search">Search</button>
        </form>

        <!-- Appointments Table -->
        <table class="appointments-table">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Doctor Name</th>
                    <th>Hospital Name</th>
                    <th>Department Name</th>
                    <th>Appointment Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($appointments_result->num_rows > 0): ?>
                    <?php while ($appointment = $appointments_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $appointment['patient_name']; ?></td>
                            <td><?php echo $appointment['doctor_name']; ?></td>
                            <td><?php echo $appointment['hospital_name']; ?></td>
                            <td><?php echo $appointment['department_name']; ?></td>
                            <td><?php echo $appointment['appointment_date']; ?></td>
                            <td>
                                <a href="updateAppointment.php?id=<?php echo $appointment['appointment_id']; ?>" class="btn-update">Update</a>
                                <a href="?delete=<?php echo $appointment['appointment_id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this appointment?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No appointments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
