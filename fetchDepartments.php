<?php
include('connect.php');

if (isset($_POST['hospital_id'])) {
    $hospital_id = $_POST['hospital_id'];

    $query = "SELECT dept_id, name FROM departments WHERE hospital_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $hospital_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="" disabled selected>Select Department</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['dept_id'] . '">' . $row['name'] . '</option>';
    }
}
?>
