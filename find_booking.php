<?php
include('db.php');

$error = "";
$found_booking = null;

// Handle the Date Update Logic
if (isset($_POST['update_dates'])) {
    $b_id = mysqli_real_escape_string($conn, $_POST['booking_id']);
    $new_in = mysqli_real_escape_string($conn, $_POST['check_in']);
    $new_out = mysqli_real_escape_string($conn, $_POST['check_out']);

    // Availability Logic: Check for overlaps excluding THIS specific booking
    $check_query = "SELECT * FROM bookings 
                    WHERE room_id = (SELECT room_id FROM bookings WHERE booking_id = '$b_id') 
                    AND booking_id != '$b_id' 
                    AND ('$new_in' < check_out AND '$new_out' > check_in)";
    
    $conflict = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($conflict) > 0) {
        $error = "The new dates selected conflict with an existing reservation for this room.";
    } else {
        $update_sql = "UPDATE bookings SET check_in = '$new_in', check_out = '$new_out' WHERE booking_id = '$b_id'";
        if (mysqli_query($conn, $update_sql)) {
            header("Location: find_booking.php?msg=updated&booking_id=$b_id");
            exit();
        }
    }
}

// Existing Search Logic
if (isset($_POST['search_booking']) || isset($_GET['booking_id'])) {
    $booking_id = mysqli_real_escape_string($conn, isset($_POST['booking_id']) ? $_POST['booking_id'] : $_GET['booking_id']);
    
    // If coming from search, we need the ID number. If coming from update redirect, we skip ID check for UI flow.
    $id_clause = isset($_POST['id_number']) ? "AND g.id_number = '".mysqli_real_escape_string($conn, $_POST['id_number'])."'" : "";

    $query = "SELECT b.*, g.full_name, r.room_number, r.room_type 
              FROM bookings b 
              JOIN guests g ON b.guest_id = g.guest_id 
              JOIN rooms r ON b.room_id = r.room_id 
              WHERE b.booking_id = '$booking_id' $id_clause";
    
    $res = mysqli_query($conn, $query);
    if (mysqli_num_rows($res) > 0) {
        $found_booking = mysqli_fetch_assoc($res);
        $_SESSION['user_booking_id'] = $found_booking['booking_id'];
    } else {
        $error = "No booking found with those details.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Reservation | Grand Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col">

    <nav class="bg-white border-b border-slate-100 px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="book_online.php" class="text-2xl font-extrabold tracking-tighter italic">GRAND<span class="text-blue-600">HOTEL</span></a>
            <a href="book_online.php" class="text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-blue-600">Back to Home</a>
        </div>
    </nav>

    <main class="flex-grow flex items-center justify-center p-6">
        <div class="max-w-md w-full">
            <?php if (!$found_booking || isset($_GET['edit'])): ?>
                <div class="bg-white p-10 rounded-[2.5rem] shadow-xl border border-slate-100">
                    <?php if(isset($_GET['edit']) && $found_booking): ?>
                        <h2 class="text-3xl font-black mb-2 tracking-tighter">Edit Stay</h2>
                        <p class="text-slate-400 text-sm mb-8 font-medium italic">Modifying Reservation #<?php echo $found_booking['booking_id']; ?></p>
                        
                        <form method="POST" class="space-y-4">
                            <input type="hidden" name="booking_id" value="<?php echo $found_booking['booking_id']; ?>">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">New Check-In</label>
                                <input type="date" name="check_in" value="<?php echo $found_booking['check_in']; ?>" required class="w-full mt-2 p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-blue-500 outline-none font-bold">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">New Check-Out</label>
                                <input type="date" name="check_out" value="<?php echo $found_booking['check_out']; ?>" required class="w-full mt-2 p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-blue-500 outline-none font-bold">
                            </div>
                            <button type="submit" name="update_dates" class="w-full bg-blue-600 text-white font-black py-5 rounded-2xl hover:bg-slate-900 transition shadow-lg uppercase tracking-widest text-xs">Update Reservation</button>
                            <a href="find_booking.php?booking_id=<?php echo $found_booking['booking_id']; ?>" class="block text-center text-[10px] font-black text-slate-400 uppercase tracking-widest mt-4">Cancel Changes</a>
                        </form>
                    <?php else: ?>
                        <h2 class="text-3xl font-black mb-2 tracking-tighter">Find Your Stay</h2>
                        <p class="text-slate-400 text-sm mb-8 font-medium">Enter your details to manage your reservation.</p>

                        <?php if($error): ?>
                            <div class="bg-red-50 text-red-500 p-4 rounded-xl text-xs font-bold mb-6 border border-red-100"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST" class="space-y-4">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Reference Number</label>
                                <input type="text" name="booking_id" required placeholder="BK-XXXX" class="w-full mt-2 p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-blue-500 outline-none font-bold">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">ID Number Used at Booking</label>
                                <input type="text" name="id_number" required class="w-full mt-2 p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-blue-500 outline-none font-bold">
                            </div>
                            <button type="submit" name="search_booking" class="w-full bg-slate-900 text-white font-black py-5 rounded-2xl hover:bg-blue-600 transition shadow-lg uppercase tracking-widest text-xs">Search Reservation</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="bg-white p-10 rounded-[2.5rem] shadow-xl border border-slate-100 text-center">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 text-2xl">📍</div>
                    <h2 class="text-2xl font-black mb-2">Booking Located!</h2>
                    <p class="text-slate-500 mb-2">Your <strong><?php echo $found_booking['room_type']; ?></strong> reservation is confirmed.</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-8"><?php echo $found_booking['check_in']; ?> — <?php echo $found_booking['check_out']; ?></p>
                    
                    <div class="space-y-3">
                        <a href="?edit=true&booking_id=<?php echo $found_booking['booking_id']; ?>" class="block w-full bg-slate-900 text-white font-black py-5 rounded-2xl hover:bg-blue-600 transition shadow-lg uppercase tracking-widest text-xs">Edit Stay Dates</a>
                        <a href="book_online.php" class="block w-full bg-slate-50 text-slate-400 font-black py-5 rounded-2xl hover:bg-slate-100 transition uppercase tracking-widest text-xs">View My Receipt</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>