<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect to login if not logged in
    exit();
}

$admin_name = $_SESSION['admin_name'];
$admin_username = $_SESSION['admin_username'];
$admin_email = $_SESSION['admin_email'];
$admin_contact = $_SESSION['admin_contact'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <section class="bg-white">
        <div class="navbar bg-sky-300 shadow-lg">
            <div class="flex-1">
                <a class="btn btn-ghost text-xl text-gray-800 font-bold">Doctors Asylum</a>
            </div>
            <div class="flex-none">
               <a href="index.php">GO BACK</a>
            </div>
        </div>

        <div class="text-center text-4xl text-black font-bold mt-8">Admin Panel</div>

        <div class="flex justify-center mt-12">
            <div class="w-full max-w-md p-8 mb-4 text-black bg-white shadow-md rounded">
                <h2 class="text-2xl font-semibold mb-4">Admin Information</h2>
                <p><strong>Name:</strong> <?php echo $admin_name; ?></p>
                <p><strong>Username:</strong> <?php echo $admin_username; ?></p>
                <p><strong>Email:</strong> <?php echo $admin_email; ?></p>
                <p><strong>Contact:</strong> <?php echo $admin_contact; ?></p>
            </div>
        </div>
    </section>
    <section>
    <div class="grid mt-12 py-8 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 bg-white gap-12 px-8">
            <!-- Manage Patient History Card -->
            <div class="card w-full max-w-xs bg-gradient-to-r from-cyan-500 to-blue-500 shadow-xl">
                <figure>
                    <img class="h-[290px] w-full object-cover rounded-t-lg" src="patient.jpg" alt="Patient History" />
                </figure>
                <div class="card-body bg-white text-gray-800 rounded-b-lg">
                    <h2 class="card-title">Manage Patients History</h2>
                    <p class="text-lg">Handle all patient histories efficiently and safely.</p>
                    <div class="card-actions justify-end">
                        <button onclick="location.href='viewPatientTable.php'" class="btn btn-primary">Visit Panel</button>
                    </div>
                </div>
            </div>

            <!-- Manage Doctors Card -->
            <div class="card w-full max-w-xs bg-gradient-to-r from-purple-500 to-indigo-500 shadow-xl">
                <figure>
                    <img class="h-[290px] w-full object-cover rounded-t-lg" src="doctors.avif" alt="Doctors" />
                </figure>
                <div class="card-body bg-white text-gray-800 rounded-b-lg">
                    <h2 class="card-title">Manage Doctors</h2>
                    <div class="card-actions justify-end">
                        <button onclick="location.href='viewDoctorsTable.php'" class="btn btn-primary">Visit</button>
                    </div>
                </div>
            </div>

            <!-- Manage Appointments Card -->
            <div class="card w-full max-w-xs bg-gradient-to-r from-orange-500 to-yellow-500 shadow-xl">
                <figure>
                    <img class="h-[290px] w-full object-cover rounded-t-lg" src="appoinment.jpg" alt="Appointments" />
                </figure>
                <div class="card-body bg-white text-gray-800 rounded-b-lg">
                    <h2 class="card-title">Manage Appointments</h2>
                    <div class="card-actions justify-end">
                        <button onclick="location.href='viewAppointmentTable.php'" class="btn btn-primary">Visit</button>
                    </div>
                </div>
            </div>

            <!-- Services Card -->
            <div class="card w-full max-w-xs bg-gradient-to-r from-green-500 to-teal-500 shadow-xl">
                <figure>
                    <img class="h-[290px] w-full object-cover rounded-t-lg" src="serve.jpeg" alt="Services" />
                </figure>
                <div class="card-body bg-white text-gray-800 rounded-b-lg">
                    <h2 class="card-title">Manage Staffs</h2>
                    <div class="card-actions justify-end">
                        <a href="viewStaffTable.php"><button class="btn btn-primary">Visit</button></a>
                    </div>
                </div>
            </div>

            <!-- Manage Medicines Card -->
            <div class="card w-full max-w-xs bg-gradient-to-r from-pink-500 to-red-500 shadow-xl">
                <figure>
                    <img class="h-[290px] w-full object-cover rounded-t-lg" src="pharma.avif" alt="Medicines" />
                </figure>
                <div class="card-body bg-white text-gray-800 rounded-b-lg">
                    <h2 class="card-title">Manage Medicines</h2>
                    <div class="card-actions justify-end">
                       <a href="viewPharmacyTable.php"> <button class="btn btn-primary">Manage</button></a>
                    </div>
                </div>
            </div>

            <!-- Manage Hospitals Card -->
            <div class="card w-full max-w-xs bg-gradient-to-r from-pink-500 to-red-500 shadow-xl">
              <figure>
                  <img class="h-[290px] w-full object-cover rounded-t-lg" src="hospital.svg" alt="Hospitals" />
              </figure>
              <div class="card-body bg-white text-gray-800 rounded-b-lg">
                  <h2 class="card-title">Manage Hospitals</h2>
                  <div class="card-actions justify-end">
                      <a href="viewHospitalTable.php"><button class="btn btn-primary">Manage</button></a>
                  </div>
              </div>
            </div>
            <div class="card w-full max-w-xs bg-gradient-to-r from-cyan-500 to-blue-500 shadow-xl">
                <figure>
                    <img class="h-[290px] w-full object-cover rounded-t-lg" src="patient.jpg" alt="Patient History" />
                </figure>
                <div class="card-body bg-white text-gray-800 rounded-b-lg">
                    <h2 class="card-title">Manage Health Tips</h2>
                    <p class="text-lg">Manage your health tips info for the users</p>
                    <div class="card-actions justify-end">
                        <button onclick="location.href='add_health_tips.php'" class="btn btn-primary">Visit Panel</button>
                    </div>
                </div>
            </div>
            <div class="card w-full max-w-xs bg-gradient-to-r from-cyan-500 to-blue-500 shadow-xl">
                <figure>
                    <img class="h-[290px] w-full object-cover rounded-t-lg" src="ambu.jpeg" alt="Patient History" />
                </figure>
                <div class="card-body bg-white text-gray-800 rounded-b-lg">
                    <h2 class="card-title">Manage Ambulaces and booked Ambulances</h2>
                    
                    <div class="card-actions justify-end">
                        <button onclick="location.href='viewAmbulanceTable.php'" class="btn btn-primary">Visit Ambulances Data</button>
                    </div>
                </div>
            </div>
        </div>
        
    </section>
</body>
</html>

