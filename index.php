<?php include('db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <title>GrandHotel | Staff Portal</title>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .serif { font-family: 'Playfair Display', serif; }
        .gold-gradient {
            background: linear-gradient(135deg, #c5a059 0%, #ecd4a4 50%, #c5a059 100%);
        }
    </style>
</head>
<body class="relative flex items-center justify-center h-screen overflow-hidden bg-gray-950">
    
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&q=80&w=2070" 
             alt="Luxury Hotel Lobby" 
             class="w-full h-full object-cover opacity-40">
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-transparent to-black/80"></div>
    </div>

    <div class="relative z-10 w-full max-w-md px-6">
        <div class="text-center mb-10">
            <h1 class="serif text-5xl text-transparent bg-clip-text gold-gradient font-bold tracking-widest uppercase">
                GrandHotel
            </h1>
            <p class="text-gray-300 tracking-[0.3em] text-xs mt-2 uppercase">Management Portal</p>
        </div>

        <div class="bg-black/40 backdrop-blur-xl p-10 rounded-3xl shadow-2xl border border-white/10 ring-1 ring-white/5">
            <h2 class="text-white text-2xl font-light mb-8 text-center tracking-tight">Staff Sign In</h2>
            
            <form action="" method="POST" class="space-y-6">
                <div class="space-y-1">
                    <label class="text-[10px] text-gray-400 uppercase tracking-widest ml-1">Username</label>
                    <input type="text" name="username" 
                           class="w-full p-4 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 outline-none focus:border-[#c5a059] focus:ring-1 focus:ring-[#c5a059] transition-all" 
                           placeholder="Enter your username" required>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-400 uppercase tracking-widest ml-1">Password</label>
                    <input type="password" name="password" 
                           class="w-full p-4 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 outline-none focus:border-[#c5a059] focus:ring-1 focus:ring-[#c5a059] transition-all" 
                           placeholder="••••••••" required>
                </div>

                <button type="submit" name="login" 
                        class="gold-gradient w-full py-4 rounded-xl text-gray-900 font-bold uppercase tracking-widest text-sm hover:scale-[1.02] active:scale-[0.98] transition-transform shadow-lg shadow-yellow-900/20">
                    Enter System
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-white/5 text-center">
                <p class="text-gray-400 text-sm">
                    New staff? <a href="register.php" class="text-[#ecd4a4] hover:underline font-medium">Request Access</a>
                </p>
            </div>
        </div>

        <p class="text-center text-gray-500 text-[10px] mt-8 uppercase tracking-[0.2em]">
            &copy; 2024 GrandHotel International. All Rights Reserved.
        </p>
    </div>
</body>
</html>

<?php
if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    $res = mysqli_query($conn, "SELECT * FROM staff WHERE username='$username'");
    $user = mysqli_fetch_assoc($res);
    
    if($user && password_verify($password, $user['password'])){
        session_start(); // Ensure session is started
        $_SESSION['staff_id'] = $user['staff_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; 
        
        header("Location: dashboard.php");
        exit();
    } else { 
        echo "<script>alert('Invalid Credentials');</script>"; 
    }
}
?>