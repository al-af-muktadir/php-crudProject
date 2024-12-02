<?php
session_start();
include('connect.php');

if (!isset($_SESSION['patient_id'])) {
    header('Location: PatientLogin.php'); // Redirect to login if not logged in
    exit();
}
if (isset($_POST['book_ambulance'])) {
    $patient_name = $_POST['patient_name'];
    $patient_id = $_SESSION['patient_id'];
    $contact_number = $_POST['contact_number'];
    $pickup_location = $_POST['pickup_location'];
    $dropoff_location = $_POST['dropoff_location'];
    $ambulance_id = $_POST['ambulance_id'];

    // Insert booking into the bookAmbulance table
    $query = "INSERT INTO bookAmbulance (patient_name,patient_id, contact_number, pickup_location, dropoff_location, ambulance_id) 
              VALUES ('$patient_name','$patient_id', '$contact_number', '$pickup_location', '$dropoff_location', '$ambulance_id')";
    
    if ($conn->query($query)) {
        // Update the ambulance status to Busy
        $update_query = "UPDATE ambulances SET status = 'Busy' WHERE ambulance_id = '$ambulance_id'";
        $conn->query($update_query);

        echo "<script>alert('Ambulance booked successfully!');</script>";
    } else {
        echo "<script>alert('Error booking ambulance: " . $conn->error . "');</script>";
    }
}

// Fetch available ambulances
$ambulance_query = "SELECT * FROM ambulances WHERE status = 'Available'";
$ambulance_result = $conn->query($ambulance_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambulance Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            
            height: 100vh;
        }
        .booking-form {
            background-color: white;
            padding: 20px;
            position:relative;
            left:550px;
            margin-top: 50px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        /* General Reset */
body, a {
    margin: 0;
    padding: 0;
    text-decoration: none;
}

/* Navbar Styles */
.navbar {
    background-color: #007bff; /* Blue */
    padding: 10px 20px;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.navbar-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
}

.logo {
    font-size: 24px;
    font-weight: bold;
    color: #fff;
    letter-spacing: 1px;
}

.back-btn {
    font-size: 16px;
    color: #fff;
    padding: 8px 12px;
    background-color: #28a745; /* Green */
    border-radius: 5px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.back-btn:hover {
    background-color: #218838;
}

        .booking-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .booking-form label {
            display: block;
            margin-bottom: 8px;
        }
        .booking-form input, .booking-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .booking-form button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .booking-form button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<nav class="navbar">
        <div class="navbar-container">
            <a href="#" class="logo">Doctors Asylum</a>
            <a href="patientPanel.php" class="back-btn">Back to Patient Panel</a>
        </div>
    </nav>
    <div class="booking-form">
        <h2>Book an Ambulance</h2>
        <form method="POST" action="">
            <label for="patient_name">Patient Name</label>
            <input type="text" id="patient_name" name="patient_name" required>

            <label for="contact_number">Contact Number</label>
            <input type="text" id="contact_number" name="contact_number" required>

            <label for="pickup_location">Pickup Location</label>
            <input type="text" id="pickup_location" name="pickup_location" required>

            <label for="dropoff_location">Drop-off Location</label>
            <input type="text" id="dropoff_location" name="dropoff_location" required>

            <label for="ambulance_id">Select Ambulance</label>
            <select id="ambulance_id" name="ambulance_id" required>
                <?php
                if ($ambulance_result->num_rows > 0) {
                    while ($row = $ambulance_result->fetch_assoc()) {
                        echo "<option value='" . $row['ambulance_id'] . "'>" . $row['vehicle_number'] . " - " . $row['type'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No ambulances available</option>";
                }
                ?>
            </select>

            <button type="submit" name="book_ambulance">Book Ambulance</button>
        </form>
    </div>
</body>
</html>
