<?php
include('connect.php');

// Handle Delete Request for Ambulances
if (isset($_GET['delete_ambulance'])) {
    $ambulance_id = $_GET['delete_ambulance'];
    $conn->query("DELETE FROM ambulances WHERE ambulance_id = $ambulance_id");
    header('Location: admin.php');
    exit();
}

// Handle Add New Ambulance
if (isset($_POST['add_ambulance'])) {
    $vehicle_number = $_POST['vehicle_number'];
    $type = $_POST['type'];
    $status = $_POST['status'];
    $location = $_POST['location'];

    $conn->query("INSERT INTO ambulances (vehicle_number, type, status, location) 
                  VALUES ('$vehicle_number', '$type', '$status', '$location')");
    header('Location: admin.php');
    exit();
}
if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    // SQL query to delete the booking
    $delete_query = "DELETE FROM bookambulance WHERE booking_id = $booking_id";

    if ($conn->query($delete_query)) {
        // Redirect back to the booking panel with a success message
        header("Location: admin.php");
    } 
} 


// Handle Update Request for Ambulance
if (isset($_POST['update_ambulance'])) {
    $ambulance_id = $_POST['ambulance_id'];
    $vehicle_number = $_POST['vehicle_number'];
    $type = $_POST['type'];
    $status = $_POST['status'];
    $location = $_POST['location'];

    $conn->query("UPDATE ambulances SET vehicle_number='$vehicle_number', type='$type', status='$status', location='$location' WHERE ambulance_id=$ambulance_id");
    header('Location: admin.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f9; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #007BFF; color: white; }
        h2 { color: #333; }
        .container { margin: 0 auto; width: 90%; }
        .btn { padding: 5px 10px; border: none; color: white; background-color: #007BFF; cursor: pointer; text-decoration: none; }
        .btn:hover { background-color: #0056b3; }
        .btn-danger { background-color: #dc3545; }
        .btn-danger:hover { background-color: #bd2130; }
        .btn-edit { background-color: #28a745; }
        .btn-edit:hover { background-color: #218838; }
        form { margin-bottom: 20px; }
        form input, form select { padding: 8px; margin-right: 10px; border: 1px solid #ddd; border-radius: 4px; }
        form button { padding: 8px 15px; background-color: #007BFF; color: white; border: none; border-radius: 4px; cursor: pointer; }
        form button:hover { background-color: #0056b3; }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Ambulances</h2>

    <!-- Add New Ambulance Form -->
    <form method="POST" action="">
        <input type="text" name="vehicle_number" placeholder="Vehicle Number" required>
        <select name="type" required>
            <option value="" disabled selected>Ambulance Type</option>
            <option value="Basic Life Support">Basic Life Support</option>
            <option value="Advanced Life Support">Advanced Life Support</option>
            <option value="Critical Care Unit">Critical Care Unit</option>
            <option value="Neonatal Ambulance">Neonatal Ambulance</option>
            <option value="Air Ambulance">Air Ambulance</option>
        </select>
        <select name="status" required>
            <option value="" disabled selected>Status</option>
            <option value="Available">Available</option>
            <option value="Busy">Busy</option>
            <option value="Under Maintenance">Under Maintenance</option>
        </select>
        <input type="text" name="location" placeholder="Location" required>
        <button type="submit" name="add_ambulance">Add Ambulance</button>
    </form>

    <!-- Display Ambulances Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Vehicle Number</th>
                <th>Type</th>
                <th>Status</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM ambulances");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['ambulance_id']}</td>
                        <td>{$row['vehicle_number']}</td>
                        <td>{$row['type']}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['location']}</td>
                        <td>
                            <a class='btn btn-edit' href='edit_ambulance.php?id={$row['ambulance_id']}'>Edit</a>
                            <a class='btn btn-danger' href='?delete_ambulance={$row['ambulance_id']}'>Delete</a>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>

    <h2>Booking Ambulances</h2>
    <table>
    <thead>
        <tr>
            <th>Booking ID</th>
            <th>Patient Name</th>
            <th>Contact Number</th>
            <th>Pickup Location</th>
            <th>Dropoff Location</th>
            <th>Booking Time</th>
            <th>Ambulance ID</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $conn->query("SELECT * FROM bookambulance");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['booking_id'] . "</td>";
            echo "<td>" . $row['patient_name'] . "</td>";
            echo "<td>" . $row['contact_number'] . "</td>";
            echo "<td>" . $row['pickup_location'] . "</td>";
            echo "<td>" . $row['dropoff_location'] . "</td>";
            echo "<td>" . $row['booking_time'] . "</td>";
            echo "<td>" . $row['ambulance_id'] . "</td>";
            echo "<td>
                    <a href='delete_booking.php?id=" . $row['booking_id'] . "' 
                       onclick='return confirm(\"Are you sure you want to delete this booking?\");'>
                        Delete
                    </a>
                  </td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
<a href="admin.php"><button class="btn">Go back</button></a>
</div>

</body>
</html>
