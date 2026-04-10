<?php include('db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <title>GrandHotel | Staff Registration</title>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .serif { font-family: 'Playfair Display', serif; }
        .gold-gradient { background: linear-gradient(135deg, #c5a059 0%, #ecd4a4 50%, #c5a059 100%); }
    </style>
</head>
<body class="relative flex items-center justify-center h-screen overflow-hidden bg-gray-950">
    
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&q=80&w=2070" 
             class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-black/60"></div>
    </div>

    <div class="relative z-10 w-full max-w-md px-6">
        <div class="text-center mb-8">
            <h1 class="serif text-4xl text-transparent bg-clip-text gold-gradient font-bold tracking-widest uppercase">GrandHotel</h1>
            <p class="text-gray-400 tracking-[0.3em] text-[10px] mt-2 uppercase">Create Staff Credentials</p>
        </div>

        <div class="bg-black/40 backdrop-blur-xl p-10 rounded-3xl shadow-2xl border border-white/10">
            <form action="" method="POST" class="space-y-5">
                <div class="space-y-1">
                    <label class="text-[10px] text-gray-400 uppercase tracking-widest ml-1">Username</label>
                    <input type="text" name="username" placeholder="Choose a username" 
                           class="w-full p-4 rounded-xl bg-white/5 border border-white/10 text-white outline-none focus:border-[#c5a059] transition-all" required>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-400 uppercase tracking-widest ml-1">Access Password</label>
                    <input type="password" name="password" placeholder="••••••••" 
                           class="w-full p-4 rounded-xl bg-white/5 border border-white/10 text-white outline-none focus:border-[#c5a059] transition-all" required>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-400 uppercase tracking-widest ml-1">Department/Role</label>
                    <select name="role" class="w-full p-4 rounded-xl bg-gray-900 border border-white/10 text-white outline-none focus:border-[#c5a059] appearance-none">
                        <option value="Staff">Staff (Reception & Bookings)</option>
                        <option value="Admin">Administrator (Full Access)</option>
                    </select>
                </div>

                <button type="submit" name="register" 
                        class="gold-gradient w-full py-4 rounded-xl text-gray-900 font-bold uppercase tracking-widest text-sm hover:scale-[1.02] transition-transform shadow-lg shadow-yellow-900/20 mt-4">
                    Register Staff
                </button>
            </form>

            <p class="text-gray-500 text-sm mt-6 text-center">
                Already registered? <a href="index.php" class="text-[#ecd4a4] hover:underline">Back to Login</a>
            </p>
        </div>
    </div>

    <?php
    if(isset($_POST['register'])){
        $user = mysqli_real_escape_string($conn, $_POST['username']);
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];
        mysqli_query($conn, "INSERT INTO staff (username, password, role) VALUES ('$user', '$pass', '$role')");
        header("Location: index.php");
    }
    ?>
</body>
</html>