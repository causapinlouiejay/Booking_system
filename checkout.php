<?php
include('db.php');
if(!isset($_SESSION['staff_id'])) { header("Location: index.php"); exit(); }

// Initialize variables to prevent undefined variable errors
$data = null;
$total_bill = 0;

if(isset($_GET['booking_id'])) {
    $b_id = mysqli_real_escape_string($conn, $_GET['booking_id']);
    
    // EXTENDED QUERY: Joined 'guests' to get the actual name for the UI
    $query = "SELECT b.*, r.price_per_night, r.room_id, g.full_name FROM bookings b 
              JOIN rooms r ON b.room_id = r.room_id 
              JOIN guests g ON b.guest_id = g.guest_id
              WHERE b.booking_id = '$b_id'";
              
    $res = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($res);

    if($data) {
        $days = (strtotime($data['check_out']) - strtotime($data['check_in'])) / 86400;
        // Ensure at least 1 day is charged if check-in/out is same day
        $days = ($days <= 0) ? 1 : $days; 
        $total_bill = $days * $data['price_per_night'];

        if(isset($_POST['confirm_checkout'])) {
            mysqli_query($conn, "UPDATE bookings SET booking_status = 'Checked-Out' WHERE booking_id = '$b_id'");
            mysqli_query($conn, "UPDATE rooms SET housekeeping_status = 'Dirty' WHERE room_id = '{$data['room_id']}'");
            header("Location: dashboard.php?msg=checked_out");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Checkout | Grand Hotel PMS</title>
</head>
<body class="bg-slate-50 flex min-h-screen">

    <div class="w-64 bg-slate-900 text-white p-6 fixed h-full shadow-2xl">
        <div class="mb-10 border-b border-slate-700 pb-6 text-center">
            <h1 class="text-2xl font-black text-blue-400 tracking-tighter">GRAND HOTEL</h1>
            <p class="text-[10px] text-slate-400 uppercase tracking-[0.2em] mt-1">Staff Terminal</p>
        </div>
        
        <nav class="space-y-2">
            <a href="dashboard.php" class="flex items-center p-3 rounded-xl hover:bg-slate-800 text-slate-300 transition">
                <span class="mr-3">📊</span> Dashboard
            </a>
            <div class="p-3 text-[10px] font-black text-slate-500 uppercase tracking-widest mt-10">Current Session</div>
            <div class="bg-blue-600/10 border border-blue-500/20 p-4 rounded-2xl">
                <p class="text-[10px] text-blue-400 uppercase font-bold">Transaction</p>
                <p class="text-sm font-bold text-white">Guest Checkout</p>
            </div>
        </nav>

        <div class="absolute bottom-10 left-6 right-6">
            <a href="dashboard.php" class="block text-center p-3 rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700 transition font-bold text-sm">Return to Desk</a>
        </div>
    </div>

    <div class="ml-64 p-10 w-full flex items-center justify-center">
        <div class="w-full max-w-xl">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-10 border-b border-slate-50 bg-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-3xl font-black text-slate-800 tracking-tight uppercase">Finalize Stay</h2>
                            <p class="text-slate-500 font-medium">Review folio and confirm room release.</p>
                        </div>
                        <span class="bg-red-100 text-red-600 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">Action Required</span>
                    </div>
                </div>

                <div class="p-10">
                    <?php if($data): ?>
                        <div class="grid grid-cols-2 gap-8 mb-10">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Guest Details</p>
                                <p class="text-xl font-bold text-slate-900 mt-1"><?php echo $row['full_name'] ?? $data['full_name']; ?></p>
                                <p class="text-xs font-mono text-slate-400">ID: #BK-<?php echo $data['booking_id']; ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Room</p>
                                <p class="text-xl font-black text-blue-600 mt-1">#<?php echo $data['room_id']; ?></p>
                            </div>
                        </div>

                        <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-xl mb-10">
                            <div class="flex justify-between items-center mb-4 pb-4 border-b border-slate-800">
                                <span class="text-slate-400 text-xs font-bold uppercase">Nights Stayed</span>
                                <span class="font-mono font-bold"><?php echo (int)$days; ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 text-xs font-bold uppercase">Total Balance</span>
                                <span class="text-4xl font-black text-blue-400">$<?php echo number_format($total_bill, 2); ?></span>
                            </div>
                        </div>

                        <form method="POST">
                            <button name="confirm_checkout" class="w-full bg-blue-600 text-white font-black py-5 rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 uppercase tracking-widest flex items-center justify-center gap-3">
                                <span>💳</span> Confirm Payment & Release Room
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="text-center py-10 bg-red-50 rounded-3xl border-2 border-dashed border-red-200">
                            <p class="text-red-600 font-black uppercase tracking-widest">Error: Invalid Session</p>
                            <p class="text-slate-500 text-sm mt-2">The booking reference was not found.</p>
                            <a href="dashboard.php" class="mt-6 inline-block bg-slate-900 text-white px-8 py-3 rounded-xl font-bold text-xs uppercase">Back to Dashboard</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <p class="text-center text-slate-400 text-[10px] font-bold uppercase mt-8 tracking-[0.3em]">© Grand Hotel Management System</p>
        </div>
    </div>
</body>
</html>