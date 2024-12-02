<?php
include 'connect.php'; // Database connection

// Handle add staff
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_staff'])) {
    $name = $_POST['name'];
    $hospital_id = $_POST['hospital_id'];
    $designation = $_POST['designation'];
    $shift = $_POST['shift'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];

    $insert_query = "INSERT INTO hospital_staff (name, hospital_id, designation, shift, contact_number, email) 
                     VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sissss", $name, $hospital_id, $designation, $shift, $contact_number, $email);

    if ($stmt->execute()) {
        header("Location: viewStaffTable.php");
        exit();
    } else {
        echo "Error adding staff: " . $conn->error;
    }
}

// Handle delete staff
if (isset($_GET['delete'])) {
    $staff_id = intval($_GET['delete']);
    $delete_query = "DELETE FROM hospital_staff WHERE staff_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $staff_id);

    if ($stmt->execute()) {
        header("Location: viewStaffTable.php");
        exit();
    } else {
        echo "Error deleting staff: " . $conn->error;
    }
}

// Fetch hospital names for the dropdown
$hospitals_query = "SELECT * FROM hospitals";
$hospitals_result = $conn->query($hospitals_query);

// Search staff
$search_query = "";
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $search_hospital = isset($_GET['hospital_id']) ? intval($_GET['hospital_id']) : '';
    $search_designation = isset($_GET['designation']) ? $_GET['designation'] : '';
    $search_shift = isset($_GET['shift']) ? $_GET['shift'] : '';

    if ($search_hospital || $search_designation || $search_shift) {
        $search_query = "WHERE 1=1";
        if ($search_hospital) $search_query .= " AND hs.hospital_id = $search_hospital";
        if ($search_designation) $search_query .= " AND hs.designation LIKE '%$search_designation%'";
        if ($search_shift) $search_query .= " AND hs.shift = '$search_shift'";
    }
}

// Fetch staff
$staff_query = "SELECT hs.*, h.name AS hospital_name 
                FROM hospital_staff hs 
                JOIN hospitals h ON hs.hospital_id = h.hospital_id 
                $search_query";
$staff_result = $conn->query($staff_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Hospital Staff</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .main-container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .success-message {
            color: green;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .search-form,
        .staff-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 30px;
        }

        .input-field,
        select {
            padding: 10px;
            font-size: 16px;
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn-submit,
        .btn-search {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-submit:hover,
        .btn-search:hover {
            background-color: #45a049;
        }

        .staff-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .staff-table th,
        .staff-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .staff-table th {
            background-color: #f2f2f2;
        }

        .btn-delete {
            color: white;
            background-color: red;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
        }

        .btn-delete:hover {
            background-color: darkred;
        }

        select {
            padding: 10px;
            font-size: 16px;
        }

        .search-form button {
            align-self: flex-start;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <h2>Manage Hospital Staff</h2>

        <!-- Success Messages -->
        <?php if (isset($_GET['added'])): ?>
            <p class="success-message">Staff added successfully!</p>
        <?php elseif (isset($_GET['deleted'])): ?>
            <p class="success-message">Staff deleted successfully!</p>
        <?php endif; ?>

        <!-- Search Form -->
        <form method="GET" class="search-form">
            <label for="hospital_id">Hospital:</label>
            <select name="hospital_id" id="hospital_id" class="input-field">
                <option value="">Select Hospital</option>
                <?php while ($hospital = $hospitals_result->fetch_assoc()): ?>
                    <option value="<?php echo $hospital['hospital_id']; ?>" 
                        <?php echo (isset($_GET['hospital_id']) && $_GET['hospital_id'] == $hospital['hospital_id']) ? 'selected' : ''; ?>>
                        <?php echo $hospital['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="designation">Designation:</label>
            <input type="text" name="designation" id="designation" class="input-field" value="<?php echo $_GET['designation'] ?? ''; ?>">

            <label for="shift">Shift:</label>
            <select name="shift" id="shift" class="input-field">
                <option value="">Select Shift</option>
                <option value="Day" <?php echo (isset($_GET['shift']) && $_GET['shift'] == 'Day') ? 'selected' : ''; ?>>Day</option>
           
                <option value="Night" <?php echo (isset($_GET['shift']) && $_GET['shift'] == 'Night') ? 'selected' : ''; ?>>Night</option>
            </select>

            <button type="submit" class="btn-search">Search</button>
        </form>

        <!-- Add Staff Form -->
        <form method="POST" class="staff-form">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" class="input-field" required>

            <label for="hospital_id">Hospital:</label>
            <select name="hospital_id" id="hospital_id" class="input-field" required>
                <option value="">Select Hospital</option>
                <?php
                $hospitals_result->data_seek(0); // Reset pointer to the start
                while ($hospital = $hospitals_result->fetch_assoc()): ?>
                    <option value="<?php echo $hospital['hospital_id']; ?>"><?php echo $hospital['name']; ?></option>
                <?php endwhile; ?>
            </select>

            <label for="designation">Designation:</label>
            <input type="text" id="designation" name="designation" class="input-field" required>

            <label for="shift">Shift:</label>
            <select id="shift" name="shift" class="input-field" required>
                <option value="">Select Shift</option>
                <option value="Morning">Day</option>
               <option value="Night">Night</option>
            </select>

            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number" class="input-field">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="input-field">

            <button type="submit" name="add_staff" class="btn-submit">Add Staff</button>
        </form>

        <!-- Staff Table -->
        <table class="staff-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Hospital</th>
                    <th>Designation</th>
                    <th>Shift</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($staff = $staff_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $staff['name']; ?></td>
                        <td><?php echo $staff['hospital_name']; ?></td>
                        <td><?php echo $staff['designation']; ?></td>
                        <td><?php echo $staff['shift']; ?></td>
                        <td><?php echo $staff['contact_number']; ?></td>
                        <td><?php echo $staff['email']; ?></td>
                        <td>
                            <a href="?delete=<?php echo $staff['staff_id']; ?>" class="btn-delete" 
                               onclick="return confirm('Are you sure you want to delete this staff?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="admin.php"><button class="btn">GO Back</button></a>
    </div>
</body>
</html>
