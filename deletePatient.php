<?php
include 'connect.php';

// Get patient ID from the URL
if (isset($_GET['id'])) {
    $patient_id = $_GET['id'];

    // Delete patient data
    $delete_query = "DELETE FROM patients WHERE patient_id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $patient_id);

    if ($delete_stmt->execute()) {
        echo "Patient deleted successfully.";
        header("Location:viewPatientTable.php");  // Redirect back to the manage patients page
    } else {
        echo "Error deleting data: " . $delete_stmt->error;
    }
} else {
    echo "Patient ID not provided.";
}
?>
