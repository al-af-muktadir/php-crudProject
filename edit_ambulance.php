<?php
include('connect.php');
// Fetch ambulance details to pre-fill the form
if (isset($_GET['id'])) {
    $ambulance_id = $_GET['id'];
    $result = $conn->query("SELECT * FROM ambulances WHERE ambulance_id = $ambulance_id");
    if ($result->num_rows == 1) {
        $ambulance = $result->fetch_assoc();
    } else {
        die("Ambulance not found!");
    }
} else {
    die("Invalid request!");
}

// Handle Update Request
if (isset($_POST['update_ambulance'])) {
    $ambulance_id = $_POST['ambulance_id'];
    $vehicle_number = $_POST['vehicle_number'];
    $type = $_POST['type'];
    $status = $_POST['status'];
    $location = $_POST['location'];

    $conn->query("UPDATE ambulances SET 
                    vehicle_number = '$vehicle_number', 
                    type = '$type', 
                    status = '$status', 
                    location = '$location' 
                  WHERE ambulance_id = $ambulance_id");
    
    header('Location: admin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ambulance</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f9; }
        .container { margin: 0 auto; width: 50%; background: white; padding: 20px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h2 { color: #333; margin-bottom: 20px; }
        form { display: flex; flex-direction: column; }
        form input, form select, form button { margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; }
        form button { background-color: #007BFF; color: white; cursor: pointer; }
        form button:hover { background-color: #0056b3; }
        .back-link { text-decoration: none; color: white; background-color: #dc3545; padding: 10px 15px; border-radius: 4px; }
        .back-link:hover { background-color: #bd2130; }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Ambulance</h2>

    <form method="POST" action="">
        <!-- Hidden input to store ambulance_id -->
        <input type="hidden" name="ambulance_id" value="<?= $ambulance['ambulance_id'] ?>">

        <!-- Vehicle Number -->
        <input type="text" name="vehicle_number" value="<?= $ambulance['vehicle_number'] ?>" placeholder="Vehicle Number" required>

        <!-- Ambulance Type -->
        <select name="type" required>
            <option value="" disabled>Ambulance Type</option>
            <option value="Basic Life Support" <?= $ambulance['type'] == "Basic Life Support" ? "selected" : "" ?>>Basic Life Support</option>
            <option value="Advanced Life Support" <?= $ambulance['type'] == "Advanced Life Support" ? "selected" : "" ?>>Advanced Life Support</option>
            <option value="Critical Care Unit" <?= $ambulance['type'] == "Critical Care Unit" ? "selected" : "" ?>>Critical Care Unit</option>
            <option value="Neonatal Ambulance" <?= $ambulance['type'] == "Neonatal Ambulance" ? "selected" : "" ?>>Neonatal Ambulance</option>
            <option value="Air Ambulance" <?= $ambulance['type'] == "Air Ambulance" ? "selected" : "" ?>>Air Ambulance</option>
        </select>

        <!-- Status -->
        <select name="status" required>
            <option value="" disabled>Status</option>
            <option value="Available" <?= $ambulance['status'] == "Available" ? "selected" : "" ?>>Available</option>
            <option value="Busy" <?= $ambulance['status'] == "Busy" ? "selected" : "" ?>>Busy</option>
            <option value="Under Maintenance" <?= $ambulance['status'] == "Under Maintenance" ? "selected" : "" ?>>Under Maintenance</option>
        </select>

        <!-- Location -->
        <input type="text" name="location" value="<?= $ambulance['location'] ?>" placeholder="Location" required>

        <!-- Update Button -->
        <button type="submit" name="update_ambulance">Update Ambulance</button>
    </form>

    <!-- Back to Admin Panel Link -->
    <a class="back-link" href="admin.php">Back to Admin Panel</a>
</div>

</body>
</html>
