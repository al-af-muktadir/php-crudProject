<?php
// Connect to database
include('connect.php');

// Handle delete functionality
if (isset($_GET['delete_id'])) {
    $doctor_id = $_GET['delete_id'];

    // Delete the doctor and associated department entry
    $delete_dept_query = "DELETE FROM departments WHERE doctor_id = '$doctor_id'";
    $conn->query($delete_dept_query);

    $delete_doctor_query = "DELETE FROM doctors WHERE doctor_id = '$doctor_id'";
    $conn->query($delete_doctor_query);

    header("Location: viewDoctorsTable.php");
    exit();
}

// Handle update functionality
if (isset($_POST['update_doctor'])) {
    $doctor_id = $_POST['doctor_id'];
    $doctor_name = $_POST['doctor_name'];
    $dept_name = $_POST['dept_name'];

    // Update doctor name
    $update_doctor_query = "UPDATE doctors SET name = '$doctor_name' WHERE doctor_id = '$doctor_id'";
    $conn->query($update_doctor_query);

    // Update department name
    $update_dept_query = "UPDATE departments SET name = '$dept_name' WHERE doctor_id = '$doctor_id'";
    $conn->query($update_dept_query);

    header("Location: viewDoctorsTable.php");
    exit();
}

// Define query to fetch all doctors and their associated hospitals
$query = "SELECT d.doctor_id, d.name AS doctor_name, h.name AS hospital_name, dept.name AS dept_name
          FROM doctors d
          JOIN hospitals h ON d.hospital_id = h.hospital_id
          LEFT JOIN departments dept ON d.doctor_id = dept.doctor_id";

$doctor_result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Doctors Table</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FFFAF1]">
    <div class="navbar flex text-balck bg-sky-300 mb-12 shadow-lg justify-between">
       
           
        <a class="text-balck bg-blue text-xl  font-bold">Doctors Asylum</a>
        <a class="text-black font-bold" href="admin.php">Go Back</a>
  
    </div>

    <div class="text-5xl  font-bold text-center text-gray-800 mb-12">
        VIEW DOCTORS TABLE
    </div>

    <!-- Doctors Table -->
    <div class="overflow-x-auto w-full px-4">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>Doctor ID</th>
                    <th>Doctor Name</th>
                    <th>Hospital Name</th>
                    <th>Department Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($doctor_result->num_rows > 0): ?>
                    <?php while ($row = $doctor_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['doctor_id']; ?></td>
                            <td><?php echo $row['doctor_name']; ?></td>
                            <td><?php echo $row['hospital_name']; ?></td>
                            <td><?php echo $row['dept_name']; ?></td>
                            <td>
                                <!-- Update Button -->
                                <button class="btn btn-warning btn-sm" onclick="openUpdateModal(<?php echo $row['doctor_id']; ?>, '<?php echo $row['doctor_name']; ?>', '<?php echo $row['dept_name']; ?>')">Update</button>
                                
                                <!-- Delete Button -->
                                <a href="?delete_id=<?php echo $row['doctor_id']; ?>" class="btn btn-error btn-sm" onclick="return confirm('Are you sure you want to delete this doctor?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No doctors found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Update Modal -->
    <div id="updateModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-96">
            <h2 class="text-2xl font-bold mb-4">Update Doctor</h2>
            <form method="POST">
                <input type="hidden" name="doctor_id" id="updateDoctorId">
                
                <div class="mb-4">
                    <label for="doctor_name" class="block text-lg">Doctor Name:</label>
                    <input type="text" name="doctor_name" id="updateDoctorName" class="input input-bordered w-full" required>
                </div>

                <div class="mb-4">
                    <label for="dept_name" class="block text-lg">Department Name:</label>
                    <input type="text" name="dept_name" id="updateDeptName" class="input input-bordered w-full" required>
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="button" class="btn btn-secondary" onclick="closeUpdateModal()">Cancel</button>
                    <button type="submit" name="update_doctor" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Open the update modal and populate fields
        function openUpdateModal(doctorId, doctorName, deptName) {
            document.getElementById('updateModal').classList.remove('hidden');
            document.getElementById('updateDoctorId').value = doctorId;
            document.getElementById('updateDoctorName').value = doctorName;
            document.getElementById('updateDeptName').value = deptName;
        }

        // Close the update modal
        function closeUpdateModal() {
            document.getElementById('updateModal').classList.add('hidden');
        }
    </script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
