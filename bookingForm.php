<?php
include('connect.php');

// Fetch hospitals
$hospital_query = "SELECT * FROM hospitals";
$hospital_result = $conn->query($hospital_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
<link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
<script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body class="bg-gray-100">
    <nav>
    <div class="navbar bg-primary text-primary-content">
  <button class="btn btn-ghost text-xl">Doctors Asylum</button>
</div>
    </nav>
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg w-96">
            <h1 class="text-2xl font-bold text-center mb-6">Book Appointment</h1>

            <form id="appointment-form" method="POST" action="bookAppointment.php">
                <!-- Patient Information -->
                <label for="patient_name" class="block text-gray-700">Name:</label>
                <input type="text" name="patient_name" id="patient_name" class="input input-bordered w-full mb-4" required>

                <label for="patient_age" class="block text-gray-700">Age:</label>
                <input type="number" name="patient_age" id="patient_age" class="input input-bordered w-full mb-4" required>

                <label for="patient_gender" class="block text-gray-700">Gender:</label>
                <select name="patient_gender" id="patient_gender" class="select select-bordered w-full mb-4" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    
                </select>

                <label for="patient_email" class="block text-gray-700">Email:</label>
                <input type="email" name="patient_email" id="patient_email" class="input input-bordered w-full mb-4">

                <label for="contact_number" class="block text-gray-700">Contact:</label>
                <input type="text" name="contact_number" id="contact_number" class="input input-bordered w-full mb-4" required>

                <!-- Hospital Selection -->
                <label for="hospital" class="block text-gray-700">Hospital:</label>
                <select name="hospital_id" id="hospital" class="select select-bordered w-full mb-4" required>
                    <option value="" disabled selected>Select Hospital</option>
                    <?php while ($row = $hospital_result->fetch_assoc()) { ?>
                        <option value="<?php echo $row['hospital_id']; ?>">
                            <?php echo $row['name']; ?>
                        </option>
                    <?php } ?>
                </select>

                <!-- Department Selection -->
                <label for="department" class="block text-gray-700">Department:</label>
                <select name="dept_id" id="department" class="select select-bordered w-full mb-4" required>
                    <option value="" disabled selected>Select Department</option>
                </select>

                <!-- Doctor Selection -->
                <label for="doctor" class="block text-gray-700">Doctor:</label>
                <select name="doctor_id" id="doctor" class="select select-bordered w-full mb-4" required>
                    <option value="" disabled selected>Select Doctor</option>
                </select>

                <button type="submit" class="btn btn-primary w-full">Book Appointment</button>
            </form>
            <a href="patientPAnel.php"><button class="btn">GO BACK</button></a>
        </div>
    </div>

    <script>
        // Fetch departments based on selected hospital
        $('#hospital').change(function() {
            const hospitalId = $(this).val();
            $('#department').html('<option value="" disabled selected>Loading...</option>');

            $.post('fetchDepartments.php', { hospital_id: hospitalId }, function(data) {
                $('#department').html(data);
            });
        });

        // Fetch doctors based on selected department and hospital
        $('#department').change(function() {
            const deptId = $(this).val();
            const hospitalId = $('#hospital').val();
            $('#doctor').html('<option value="" disabled selected>Loading...</option>');

            $.post('fetchDoctors.php', { hospital_id: hospitalId, dept_id: deptId }, function(data) {
                $('#doctor').html(data);
            });
        });
    </script>
</body>
</html>
