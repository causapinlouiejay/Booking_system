<?php 
include('db.php'); 
if(!isset($_SESSION['staff_id'])) {
    header("Location: index.php");
    exit();
}

// Action: Mark room as Cleaned
if(isset($_GET['mark_clean'])) {
    $rid = mysqli_real_escape_string($conn, $_GET['mark_clean']);
    mysqli_query($conn, "UPDATE rooms SET housekeeping_status = 'Clean' WHERE room_id = '$rid'");
    header("Location: housekeeping.php?msg=cleaned");
}

// Fetch all rooms that are currently "Dirty"
$dirty_rooms = mysqli_query($conn, "SELECT * FROM rooms WHERE housekeeping_status = 'Dirty' ORDER BY room_number ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Housekeeping | Grand Hotel</title>
</head>
<body class="bg-slate-50 flex min-h-screen font-['Plus_Jakarta_Sans']">

    <div class="w-64 bg-slate-900 text-white p-6 fixed h-full shadow-2xl">
        <div class="mb-10 border-b border-slate-700 pb-6 text-center">
            <h1 class="text-2xl font-black text-blue-400 tracking-tighter">GRAND HOTEL</h1>
            <p class="text-[10px] text-slate-400 uppercase tracking-[0.2em] mt-1">Staff Terminal</p>
        </div>
        
        <nav class="space-y-2">
            <a href="dashboard.php" class="flex items-center p-3 rounded-xl hover:bg-slate-800 transition text-slate-400">
                <span class="mr-3">📊</span> Dashboard
            </a>
            <a href="manage_rooms.php" class="flex items-center p-3 rounded-xl hover:bg-slate-800 transition text-slate-400">
                <span class="mr-3">🔑</span> Manage Rooms
            </a>
            <a href="housekeeping.php" class="flex items-center p-3 rounded-xl bg-blue-600 shadow-lg text-white transition">
                <span class="mr-3">🧹</span> Housekeeping
            </a>
            <div class="pt-10">
                <a href="logout.php" class="flex items-center p-3 rounded-xl text-red-400 hover:bg-red-500/10 transition">
                    <span class="mr-3">🚪</span> Logout
                </a>
            </div>
        </nav>
    </div>

    <main class="flex-1 ml-64 p-12">
        <header class="flex justify-between items-end mb-12">
            <div>
                <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mb-2">Maintenance Module</p>
                <h2 class="text-4xl font-black text-slate-900 tracking-tight">Housekeeping <span class="text-slate-300">Queue</span></h2>
            </div>
            <div class="text-right">
                <span class="bg-red-100 text-red-600 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-widest">
                    <?php echo mysqli_num_rows($dirty_rooms); ?> Rooms Need Attention
                </span>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if(mysqli_num_rows($dirty_rooms) > 0): ?>
                <?php while($room = mysqli_fetch_assoc($dirty_rooms)): ?>
                    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 group">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-12 h-12 bg-slate-900 text-white rounded-2xl flex items-center justify-center font-black">
                                <?php echo $room['room_number']; ?>
                            </div>
                            <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-lg text-[10px] font-black uppercase">Requires Cleaning</span>
                        </div>
                        
                        <div class="mb-8">
                            <h3 class="text-xl font-bold text-slate-800"><?php echo $room['room_type']; ?></h3>
                            <p class="text-slate-400 text-sm mt-1 italic">Last Guest: Recently Checked Out</p>
                        </div>

                        <a href="?mark_clean=<?php echo $room['room_id']; ?>" 
                           class="block w-full text-center bg-slate-50 text-slate-900 border-2 border-slate-100 py-4 rounded-2xl font-black uppercase tracking-widest text-xs group-hover:bg-emerald-500 group-hover:text-white group-hover:border-emerald-500 transition-all">
                           Finish Cleaning
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-full py-20 text-center bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                    <div class="text-5xl mb-4">✨</div>
                    <h3 class="text-xl font-bold text-slate-800">All Rooms are Pristine</h3>
                    <p class="text-slate-400">There are no rooms currently marked as dirty.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>