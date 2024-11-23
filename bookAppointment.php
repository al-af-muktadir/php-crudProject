<?php
include('connect.php');
session_start(); // Start the session to access patient ID

if (!isset($_SESSION['patient_id'])) {
    // Redirect to login if patient is not logged in
    header("Location: patientLogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch patient_id from the session
    $patient_id = $_SESSION['patient_id'];

    // Retrieve form data
    $name = $_POST['patient_name'];
    $age = $_POST['patient_age'];
    $gender = $_POST['patient_gender'];
    $email = $_POST['patient_email'];
    $contact = $_POST['contact_number'];
    $hospital_id = $_POST['hospital_id'];
    $dept_id = $_POST['dept_id'];
    $doctor_id = $_POST['doctor_id'];

    // Query to insert appointment details
    $query = "INSERT INTO appointments (patient_id, patient_name, patient_age, patient_gender, patient_email, contact_number, hospital_id, dept_id, doctor_id) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);

    // Bind the parameters
    $stmt->bind_param("isisssiii", $patient_id, $name, $age, $gender, $email, $contact, $hospital_id, $dept_id, $doctor_id);

    // Execute the query and handle result
    if ($stmt->execute()) {
        echo "Appointment booked successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
