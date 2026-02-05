<?php include('db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Hotel Admin Login</title>
</head>
<body class="bg-slate-900 flex items-center justify-center h-screen">
    <div class="bg-white/10 backdrop-blur-md p-8 rounded-2xl shadow-2xl w-96 border border-white/20">
        <h2 class="text-white text-3xl font-bold mb-6 text-center">Staff Login</h2>
        <form action="" method="POST" class="space-y-4">
            <input type="text" name="username" placeholder="Username" class="w-full p-3 rounded bg-white/5 border border-white/20 text-white placeholder-gray-400 outline-none focus:border-blue-500" required>
            <input type="password" name="password" placeholder="Password" class="w-full p-3 rounded bg-white/5 border border-white/20 text-white placeholder-gray-400 outline-none focus:border-blue-500" required>
            <button type="submit" name="login" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">Enter System</button>
        </form>
        <p class="text-gray-400 text-sm mt-4 text-center">New staff? <a href="register.php" class="text-blue-400">Register here</a></p>
    </div>
</body>
</html>

<?php
if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $res = mysqli_query($conn, "SELECT * FROM staff WHERE username='$username'");
    $user = mysqli_fetch_assoc($res);
    if($user && password_verify($password, $user['password'])){
        $_SESSION['staff_id'] = $user['staff_id'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
    } else { echo "<script>alert('Invalid Credentials');</script>"; }
}
?>