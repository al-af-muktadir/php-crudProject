<?php
include 'connect.php';  // Database connection

// Fetch all patient details
$query = "SELECT * FROM patients";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patients</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Add your styles here */
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
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #007BFF;
            color: white;
        }

        .action-btns {
            display: flex;
            justify-content: space-around;
        }

        .action-btns button {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            margin-right: 5px;
        }

        .action-btns button.delete {
            background-color: #dc3545;
        }

        .action-btns button.edit {
            background-color: #ffc107;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="adminDashboard.php" class="logo">Admin Panel</a>
        <div class="navbar-links">
            <a href="admin.php" class="nav-link">go back</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container">
        <h2>Manage Patients Data</h2>
        <!-- Patient Table -->
        <table>
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Contact</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['patient_id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['age']; ?></td>
                        <td><?php echo $row['gender']; ?></td>
                        <td><?php echo $row['contact']; ?></td>
                        <td class="action-btns">
                            <!-- Edit and Delete buttons -->
                            <a href="Update.php?id=<?php echo $row['patient_id']; ?>">
                                <button class="edit">Edit</button>
                            </a>
                            <a href="deletePatient.php?id=<?php echo $row['patient_id']; ?>" onclick="return confirm('Are you sure you want to delete this patient?')">
                                <button class="delete">Delete</button>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
