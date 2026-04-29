<?php
session_start();
require 'db.php';

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $pass = md5($_POST['password']);
    $q = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$pass'");
    if(mysqli_num_rows($q) == 1){
        $_SESSION['user'] = mysqli_fetch_assoc($q);
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>FarmCare Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-emerald-50 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-xl shadow-lg w-96">
        <h1 class="text-2xl font-bold text-emerald-800 mb-6">🌾 FarmCare</h1>
        <?php if(isset($error)) echo "<p class='text-red-500 mb-4'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required class="w-full border p-2 mb-3 rounded">
            <input type="password" name="password" placeholder="Password" required class="w-full border p-2 mb-4 rounded">
            <button name="login" class="w-full bg-emerald-600 text-white py-2 rounded hover:bg-emerald-700">Login</button>
        </form>
        <p class="text-sm mt-3 text-gray-500">Demo: farmer@farm.com / 123456</p>
    </div>
</body>
</html>