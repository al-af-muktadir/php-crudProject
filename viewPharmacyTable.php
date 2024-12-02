<?php
include 'connect.php'; // Database connection

// Fetch pharmacy details along with their hospital names
$query = "
    SELECT p.pharmacy_id, p.name AS pharmacy_name, p.location, p.contact_number, p.email, h.name AS hospital_name
    FROM pharmacy p
    JOIN hospitals h ON p.hospital_id = h.hospital_id
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Pharmacies</title>
    
<link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
<script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .main-container {
            width: 90%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .btn-view-medicines {
            padding: 8px 12px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
        }

        .btn-view-medicines:hover {
            background-color: #0056b3;
        }
        .navbar-custom {
            background-color: #3b82f6; /* Blue background */
            padding: 1rem 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom a {
            color: white;
            font-size: 1.2rem;
            margin-left: 1.5rem;
            font-weight: bold;
            transition: color 0.3s ease-in-out;
        }

        .navbar-custom a:hover {
            color: #ffcb47; /* Golden color on hover */
        }

        .navbar-custom .menu {
            display: flex;
            align-items: center;
        }

        .navbar-custom .menu li {
            list-style: none;
        }

        .navbar-logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ffffff;
        }

        .header-custom {
            background-color: #f8fafc;
            padding: 2rem;
            text-align: center;
            border-bottom: 3px solid #3b82f6;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .header-custom h1 {
            font-size: 3rem;
            font-weight: 700;
            color: #333;
        }
        .btn-custom {
    background-color: #3b82f6; /* Blue background */
    color: white;              /* White text */
    padding: 0.75rem 2rem;     /* Vertical and horizontal padding */
    font-size: 1rem;}
    </style>
</head>

<body>
<nav class="navbar-custom">
        <div class="flex justify-between items-center">
            <a href="#" class="navbar-logo">Doctors Asylum</a>
            
                
             <div>
             <a href="admin.php"><button class="btn-custom">Go back</button></a>
             </div> 
            
        </div>
    </nav>


    <!-- Header -->
    <header class="header-custom">
        <h1 class="text-4xl font-extrabold text-gray-700">Manage Pharmacy List</h1>
    </header>
    <div class="main-container">
        <h2>Pharmacies List</h2>

        <table>
            <thead>
                <tr>
                    <th>Pharmacy Name</th>
                    <th>Location</th>
                    <th>Contact Number</th>
                    <th>Email</th>
                    <th>Hospital Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['pharmacy_name']; ?></td>
                            <td><?php echo $row['location']; ?></td>
                            <td><?php echo $row['contact_number']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['hospital_name']; ?></td>
                            <td>
                                <a href="viewMedicines.php?pharmacy_id=<?php echo $row['pharmacy_id']; ?>" class="btn-view-medicines">
                                    View Medicines
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">No pharmacies found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
