<?php include('db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-slate-900 flex items-center justify-center h-screen">
    <div class="bg-white/10 backdrop-blur-md p-8 rounded-2xl shadow-2xl w-96 border border-white/20">
        <h2 class="text-white text-3xl font-bold mb-6 text-center">Staff Registration</h2>
        <form action="" method="POST" class="space-y-4">
            /* Replace the form in register.php with this */
<form action="" method="POST" class="space-y-4">
    <input type="text" name="username" placeholder="Choose Username" class="w-full p-3 rounded bg-white/5 border border-white/20 text-white outline-none" required>
    <input type="password" name="password" placeholder="Choose Password" class="w-full p-3 rounded bg-white/5 border border-white/20 text-white outline-none" required>
    <select name="role" class="w-full p-3 rounded bg-slate-800 border border-white/20 text-white outline-none">
        <option value="Staff">Staff (Bookings Only)</option>
        <option value="Admin">Admin (Full Access)</option>
    </select>
    <button type="submit" name="register" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg transition">Create Account</button>
</form>

<?php
if(isset($_POST['register'])){
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    mysqli_query($conn, "INSERT INTO staff (username, password, role) VALUES ('$user', '$pass', '$role')");
    header("Location: index.php");
}
?>