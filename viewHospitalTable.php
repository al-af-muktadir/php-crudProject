<?php
include 'connect.php';


// Handle form submission to add a hospital
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hospital_name = $_POST['hospital_name'];
    $location = $_POST['location'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];

    // Insert query
    $insert_query = "INSERT INTO hospitals (name, location, contact, email) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ssss", $hospital_name, $location, $contact_number, $email);

    if ($stmt->execute()) {
        header("Location: viewHospitalTable.php"); // Redirect after successful addition
        exit();
    } else {
        echo "Error adding hospital: " . $stmt->error;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your external CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .main-container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .hospital-form {
            display: flex;
            flex-direction: column;
            margin-bottom: 30px;
        }
        .hospital-form label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        .input-field {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        .btn-submit {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #0056b3;
        }
        .hospital-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .hospital-table th, .hospital-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .hospital-table th {
            background-color: #f4f4f4;
        }
        .btn-delete {
            padding: 5px 10px;
            background-color: #dc3545;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <h2>Manage Hospitals</h2>
        <?php if (isset($_GET['success'])): ?>
            <p class="success-message">Hospital added successfully!</p>
        <?php elseif (isset($_GET['deleted'])): ?>
            <p class="success-message">Hospital deleted successfully!</p>
        <?php endif; ?>

        <!-- Form for adding hospitals -->
        <form method="POST" class="hospital-form">
            <label for="hospital_name">Hospital Name:</label>
            <input type="text" id="hospital_name" name="hospital_name" class="input-field" required>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" class="input-field" required>

            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number" class="input-field" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="input-field" required>

            <button type="submit" class="btn-submit">Add Hospital</button>
        </form>

        <!-- Hospital Table -->
        <table class="hospital-table">
            <thead>
                <tr>
                    <th>Hospital Name</th>
                    <th>Location</th>
                    <th>Contact Number</th>
                    <th>Email</th>
                   
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch hospitals from the database
                $query = "SELECT * FROM hospitals";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['location']; ?></td>
                        <td><?php echo $row['contact']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                    

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
   
    <a href="admin.php"><button class="btn">GO BACK</button></a>
<a href="admin.html"><button class="btn">GO back</button></a>
</body>

</html>
