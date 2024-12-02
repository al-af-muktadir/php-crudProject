<?php
// Include database connection
include('connect.php');

// Add health tip functionality
if (isset($_POST['add_health_tip'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Insert query
    $query = "INSERT INTO health_tips (title, description) VALUES ('$title', '$description')";
    if (mysqli_query($conn, $query)) {
        echo "Health tip added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Update health tip functionality
if (isset($_POST['update_health_tip'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Update query
    $query = "UPDATE health_tips SET title = '$title', description = '$description' WHERE tip_id = '$id'";
    if (mysqli_query($conn, $query)) {
        echo "Health tip updated successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Delete health tip functionality
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Delete query
    $query = "DELETE FROM health_tips WHERE tip_id = '$id'";
    if (mysqli_query($conn, $query)) {
        echo "Health tip deleted successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch all health tips from the database
$query = "SELECT * FROM health_tips";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Health Tips</title>
    
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .btn-custom1 {
    background-color: blue; /* Blue background */
    color: white;              /* White text */
    padding: 0.75rem 2rem;     /* Vertical and horizontal padding */
    font-size: 1rem;
    border-radius: 8px;

}
    .btn-custom2 {
    background-color: red; /* Blue background */
    color: white;   
    border-radius: 8px;           /* White text */
    padding: 0.75rem 2rem;     /* Vertical and horizontal padding */
    font-size: 1rem;}
    </style>
</head>
<body>
    <div class="container mx-auto mt-8">
        <h1 class="text-4xl font-semibold text-center mb-6">Manage Health Tips</h1>

        <!-- Add Health Tip Form -->
        <div class="form-container">
            <h2 class="text-2xl font-bold mb-4">Add New Health Tip</h2>
            <form action="add_health_tips.php" method="POST">
                <input type="text" name="title" class="input input-bordered w-full mb-4 p-2" placeholder="Health Tip Title" required>
                <textarea name="description" class="input input-bordered w-full mb-4 p-2" placeholder="Health Tip Description" required></textarea>
                <button type="submit" name="add_health_tip" class="btn btn-primary w-full">Add Health Tip</button>
            </form>
        </div>

        <!-- Update Health Tip Form (Will show when updating) -->
        <div class="form-container mt-8" id="update-form" style="display:none;">
            <h2 class="text-2xl font-bold mb-4">Update Health Tip</h2>
            <form action="add_health_tips.php" method="POST">
                <input type="hidden" id="update-id" name="id">
                <input type="text" id="update-title" name="title" class="input input-bordered w-full mb-4 p-2" placeholder="Health Tip Title" required>
                <textarea id="update-description" name="description" class="input input-bordered w-full mb-4 p-2" placeholder="Health Tip Description" required></textarea>
                <button type="submit" name="update_health_tip" class="btn btn-primary w-full">Update Health Tip</button>
            </form>
        </div>

        <!-- Health Tips Table -->
        <div class="mt-8">
            <h2 class="text-3xl font-semibold text-center mb-6">Existing Health Tips</h2>
            <table class="table-auto w-full border border-gray-300">
                <thead>
                    <tr>
                        <th class="border-b px-4 py-2">ID</th>
                        <th class="border-b px-4 py-2">Title</th>
                        <th class="border-b px-4 py-2">Description</th>
                        <th class="border-b px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td class="border-b px-4 py-2"><?php echo $row['tip_id']; ?></td>
                            <td class="border-b px-4 py-2"><?php echo $row['title']; ?></td>
                            <td class="border-b px-4 py-2"><?php echo $row['description']; ?></td>
                            <td class="border-b px-4 py-2">
                                <button class="btn-custom1" onclick="editHealthTip(<?php echo $row['tip_id']; ?>, '<?php echo $row['title']; ?>', '<?php echo $row['description']; ?>')" class="btn btn-info mr-2">Edit</button>
                                <a href="add_health_tips.php?delete=<?php echo $row['tip_id']; ?>" class="btn-custom2">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Function to display the update form
        function editHealthTip(id, title, description) {
            document.getElementById('update-form').style.display = 'block';
            document.getElementById('update-id').value = id;
            document.getElementById('update-title').value = title;
            document.getElementById('update-description').value = description;
        }
    </script>
    <a href="admin.php"><button class="btn">Go Back</button></a>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
