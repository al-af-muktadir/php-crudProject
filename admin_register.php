<?php
include('connect.php'); // Include database connection

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $contact = $_POST['contact_number'];
    $password = $_POST['password']; // No encryption as per request

    // Insert admin into the database
    $query = "INSERT INTO Admin (name, username, email, contact_number, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $name, $username, $email, $contact, $password);

    if ($stmt->execute()) {
        session_start();
        $_SESSION['admin_id'] = $stmt->insert_id; // Save admin ID in session
        $_SESSION['admin_name'] = $name;
        $_SESSION['admin_username'] = $username;
        $_SESSION['admin_email'] = $email;
        $_SESSION['admin_contact'] = $contact;

        // Redirect to admin.php
        header("Location: admin.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <section class="bg-[#FFFAF1]">
        <div class="flex justify-center items-center min-h-screen">
            <div class="w-full max-w-md p-8 bg-white shadow-md rounded">
                <h2 class="text-2xl font-bold text-center">Admin Registration</h2>
                <form method="POST" class="mt-8">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-semibold">Name</label>
                        <input type="text" name="name" id="name" required class="input input-bordered w-full mt-1" />
                    </div>
                    <div class="mb-4">
                        <label for="username" class="block text-sm font-semibold">Username</label>
                        <input type="text" name="username" id="username" required class="input input-bordered w-full mt-1" />
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-semibold">Email</label>
                        <input type="email" name="email" id="email" required class="input input-bordered w-full mt-1" />
                    </div>
                    <div class="mb-4">
                        <label for="contact_number" class="block text-sm font-semibold">Contact Number</label>
                        <input type="text" name="contact_number" id="contact_number" required class="input input-bordered w-full mt-1" />
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-semibold">Password</label>
                        <input type="password" name="password" id="password" required class="input input-bordered w-full mt-1" />
                    </div>
                    <button type="submit" name="register" class="btn btn-primary w-full">Register</button>
                </form>
                <p class="mt-4 text-center">
                    Already registered? <a href="admin_login.php" class="text-blue-500">Login here</a>
                </p>
            </div>
        </div>
    </section>
</body>
</html>
