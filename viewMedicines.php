<?php
include('connect.php');
// Check if pharmacy_id is provided
if (!isset($_GET['pharmacy_id'])) {
    die("No pharmacy selected.");
}

$pharmacy_id = $_GET['pharmacy_id'];

// Connect to the database


// Initialize search parameters
$type = isset($_POST['type']) ? $_POST['type'] : '';
$purpose = isset($_POST['purpose']) ? $_POST['purpose'] : '';

// SQL query to fetch medicines based on search filters
$sql = "SELECT * FROM medicine WHERE pharmacy_id = ?";
if ($type != '') {
    $sql .= " AND type = ?";
}
if ($purpose != '') {
    $sql .= " AND purpose = ?";
}

$stmt = $conn->prepare($sql);

// Bind parameters for prepared statement
if ($type != '' && $purpose != '') {
    $stmt->bind_param("iss", $pharmacy_id, $type, $purpose);
} elseif ($type != '') {
    $stmt->bind_param("is", $pharmacy_id, $type);
} elseif ($purpose != '') {
    $stmt->bind_param("is", $pharmacy_id, $purpose);
} else {
    $stmt->bind_param("i", $pharmacy_id);
}

$stmt->execute();
$result = $stmt->get_result();

// Handle delete and update requests
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM medicine WHERE medicine_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $delete_id);
    $delete_stmt->execute();
    header("Location: viewMedicines.php?pharmacy_id=" . $pharmacy_id);
}

if (isset($_POST['update_quantity'])) {
    $medicine_id = $_POST['medicine_id'];
    $new_quantity = $_POST['quantity'];
    $update_sql = "UPDATE medicine SET quantity = ? WHERE medicine_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $new_quantity, $medicine_id);
    $update_stmt->execute();
    header("Location: viewMedicines.php?pharmacy_id=" . $pharmacy_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicines for Pharmacy</title>
    <style>
        /* Styling for the search form */
form {
    margin: 20px 0;
    padding: 10px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 5px;
}

form div {
    margin-bottom: 15px;
}

form label {
    margin-right: 10px;
}

form select {
    padding: 8px;
    width: 200px;
}

form button {
    padding: 8px 12px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
}

form button:hover {
    background-color: #0056b3;
}

/* Styling for the medicines table */
.medicine-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.medicine-table th, .medicine-table td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
}

.medicine-table th {
    background-color: #f2f2f2;
}

.medicine-table td {
    background-color: #fff;
}
/* Styling for the medicines table */
.medicine-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.medicine-table th, .medicine-table td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
}

.medicine-table th {
    background-color: #f2f2f2;
}

.medicine-table td {
    background-color: #fff;
}

/* Styling for Delete and Update buttons */
.medicine-table a {
    color: red;
    text-decoration: none;
    font-weight: bold;
}

.medicine-table a:hover {
    text-decoration: underline;
}

.medicine-table form {
    display: inline-block;
}

.medicine-table input[type="number"] {
    padding: 5px;
    width: 60px;
}

.medicine-table button {
    padding: 5px 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
}

.medicine-table button:hover {
    background-color: #0056b3;
}


    </style>
</head>
<body>
    <h1>Medicines for Pharmacy ID: <?php echo $pharmacy_id; ?></h1>

    <!-- Search Form -->
    <form method="POST" action="viewMedicines.php?pharmacy_id=<?php echo $pharmacy_id; ?>">
        <div>
            <label for="type">Type:</label>
            <select name="type" id="type">
                <option value="">Select Type</option>
                <option value="Tablet" <?php echo ($type == 'Tablet') ? 'selected' : ''; ?>>Tablet</option>
                <option value="Capsule" <?php echo ($type == 'Capsule') ? 'selected' : ''; ?>>Capsule</option>
                <option value="Syrup" <?php echo ($type == 'Syrup') ? 'selected' : ''; ?>>Syrup</option>
                <option value="Inhaler" <?php echo ($type == 'Inhaler') ? 'selected' : ''; ?>>Inhaler</option>
            </select>
        </div>

        <div>
            <label for="purpose">Purpose:</label>
            <select name="purpose" id="purpose">
                <option value="">Select Purpose</option>
                <option value="Pain Relief" <?php echo ($purpose == 'Pain Relief') ? 'selected' : ''; ?>>Pain Relief</option>
                <option value="Allergy Relief" <?php echo ($purpose == 'Allergy Relief') ? 'selected' : ''; ?>>Allergy Relief</option>
                <option value="Antibiotic" <?php echo ($purpose == 'Antibiotic') ? 'selected' : ''; ?>>Antibiotic</option>
                <option value="Cough Suppressant" <?php echo ($purpose == 'Cough Suppressant') ? 'selected' : ''; ?>>Cough Suppressant</option>
                <option value="Anti-inflammatory" <?php echo ($purpose == 'Anti-inflammatory') ? 'selected' : ''; ?>>Anti-inflammatory</option>
            </select>
        </div>

        <button type="submit">Search</button>
    </form>

    <!-- Medicines Table -->
    <table class="medicine-table">
        <thead>
            <tr>
                <th>Medicine Name</th>
                <th>Type</th>
                <th>Price</th>
                <th>Expiry Date</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $medicine_id = $row['medicine_id'];
                    echo "<tr>
                            <td>{$row['name']}</td>
                            <td>{$row['type']}</td>
                            <td>{$row['price']}</td>
                            <td>{$row['expiry_date']}</td>
                            <td>{$row['quantity']}</td>
                            <td>
                                <a href='viewMedicines.php?pharmacy_id={$pharmacy_id}&delete_id={$medicine_id}' onclick='return confirm(\"Are you sure you want to delete?\")'>Delete</a> |
                                <form method='POST' action='viewMedicines.php?pharmacy_id={$pharmacy_id}' style='display:inline;'>
                                    <input type='hidden' name='medicine_id' value='{$medicine_id}'>
                                    <input type='number' name='quantity' min='1' value='{$row['quantity']}' required>
                                    <button type='submit' name='update_quantity'>Update Quantity</button>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No medicines found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
