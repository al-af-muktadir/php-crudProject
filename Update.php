<?php
include 'connect.php';

// Get patient ID from the URL
if (isset($_GET['id'])) {
    $patient_id = $_GET['id'];

    // Fetch patient details
    $query = "SELECT * FROM patients WHERE patient_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $patient = $stmt->get_result()->fetch_assoc();
} else {
    echo "Patient ID not found.";
    exit();
}

// Handle form submission to update patient data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];

    // Update patient data in the database
    $update_query = "UPDATE patients SET name = ?, email = ?, age = ?, gender = ?, contact = ? WHERE patient_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssisii", $name, $email, $age, $gender, $contact, $patient_id);
    
    if ($update_stmt->execute()) {
        echo "Patient data updated successfully.";
        header("Location: viewPatientTAble.php");  // Redirect back to the manage patients page
    } else {
        echo "Error updating data: " . $update_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient</title>
    <link rel="stylesheet" href="styles.css">  <!-- Link to your external CSS file -->

<style>
    /* General Page Styles */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f7fa;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Main Container */
.main-container {
    background-color: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 600px;
    box-sizing: border-box;
    text-align: center;
}

/* Heading */
h2 {
    font-size: 24px;
    color: #333;
    margin-bottom: 20px;
    font-weight: 600;
}

/* Form Styles */
form {
    display: flex;
    flex-direction: column;
}

/* Label Styles */
label {
    font-size: 16px;
    color: #555;
    text-align: left;
    margin-bottom: 6px;
    margin-left: 10px;
}

/* Input Field Styles */
input[type="text"],
input[type="email"],
input[type="number"],
select {
    font-size: 16px;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #fafafa;
    transition: border 0.3s ease, background-color 0.3s ease;
}

/* Input Focus Styles */
input[type="text"]:focus,
input[type="email"]:focus,
input[type="number"]:focus,
select:focus {
    border-color: #007bff;
    background-color: #f1f9ff;
    outline: none;
}

/* Button Styles */
button {
    background-color: #007bff;
    color: white;
    padding: 12px;
    font-size: 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 20px;
}

/* Button Hover Effect */
button:hover {
    background-color: #0056b3;
}

/* Responsive Design */
@media (max-width: 600px) {
    .main-container {
        padding: 20px;
        width: 90%;
    }

    h2 {
        font-size: 20px;
    }

    label {
        font-size: 14px;
    }

    input[type="text"],
    input[type="email"],
    input[type="number"],
    select {
        font-size: 14px;
        padding: 10px;
    }

    button {
        font-size: 14px;
        padding: 10px;
    }
}

</style>
</head>
<body>
    <!-- Main Container -->
    <div class="form-container">
        <h2 class="form-title">Edit Patient Details</h2>
        
        <form method="POST" class="update-form">
            <!-- Name Field -->
            <label for="name" class="form-label">Name:</label>
            <input type="text" name="name" id="name" class="input-field" value="<?php echo $patient['name']; ?>" required><br>

            <!-- Email Field -->
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email" class="input-field" value="<?php echo $patient['email']; ?>" required><br>

            <!-- Age Field -->
            <label for="age" class="form-label">Age:</label>
            <input type="number" name="age" id="age" class="input-field" value="<?php echo $patient['age']; ?>" required><br>

            <!-- Gender Field -->
            <label for="gender" class="form-label">Gender:</label>
            <select name="gender" id="gender" class="input-field" required>
                <option value="Male" <?php echo ($patient['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($patient['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
            </select><br>

            <!-- Contact Field -->
            <label for="contact" class="form-label">Contact:</label>
            <input type="text" name="contact" id="contact" class="input-field" value="<?php echo $patient['contact']; ?>" required><br>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit">Update Patient</button>
        </form>
    </div>
</body>
</html>
