<?php
include('connect.php');
// Check if the booking ID is provided
if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    // SQL query to delete the booking
    $delete_query = "DELETE FROM bookambulance WHERE booking_id = $booking_id";

    if ($conn->query($delete_query)) {
        // Redirect back to the booking management panel with a success message
        header("Location: admin.php?msg=Booking deleted successfully");
        exit();
    } else {
        // Redirect with an error message
        header("Location: admin.php?msg=Error deleting booking");
        exit();
    }
} else {
    // Redirect if no ID is provided
    header("Location: admin.php?msg=Invalid request");
    exit();
}

?>
