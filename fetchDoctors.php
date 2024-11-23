<?php
include('connect.php');

if (isset($_POST['hospital_id']) && isset($_POST['dept_id'])) {
    $hospital_id = $_POST['hospital_id'];
    $dept_id = $_POST['dept_id'];

    $query = "SELECT doctor_id, name FROM doctors WHERE hospital_id = ? AND department_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $hospital_id, $dept_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="" disabled selected>Select Doctor</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['doctor_id'] . '">' . $row['name'] . '</option>';
    }
}
?>
