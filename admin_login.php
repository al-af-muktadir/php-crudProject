<?php
session_start();
include('connect.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM Admin WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if ($password === $admin['password']) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_contact'] = $admin['contact_number'];

            header("Location: admin.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No admin found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <section class="bg-[#FFFAF1]">
        <div class="flex justify-center items-center min-h-screen">
            <div class="w-full max-w-md p-8 bg-white shadow-md rounded">
                <h2 class="text-2xl font-bold text-center">Admin Login</h2>
                <form  method="POST" class="mt-8">
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-semibold">Email</label>
                        <input type="email" name="email" id="email" required class="input input-bordered w-full mt-1" />
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-semibold">Password</label>
                        <input type="password" name="password" id="password" required class="input input-bordered w-full mt-1" />
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-full">Login</button>
                </form>
                <p class="mt-4 text-center">
                    Don't have an account? <a href="admin_register.php" class="text-blue-500">Register here</a>
                </p>
            </div>
        </div>
    </section>
</body>
</html>
