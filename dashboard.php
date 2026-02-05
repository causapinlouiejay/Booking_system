<?php 
include('db.php'); 
if(!isset($_SESSION['staff_id'])) header("Location: index.php");

$success = isset($_GET['success']) ? true : false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Hotel PMS | Admin Dashboard</title>
</head>
<body class="bg-slate-50 flex min-h-screen">

    <div class="w-64 bg-slate-900 text-white p-6 fixed h-full shadow-2xl">
        <div class="mb-10 border-b border-slate-700 pb-6 text-center">
            <h1 class="text-2xl font-black text-blue-400 tracking-tighter">GRAND HOTEL</h1>
            <p class="text-[10px] text-slate-400 uppercase tracking-[0.2em] mt-1">Staff Terminal</p>
        </div>
        
        <nav class="space-y-2">
            <a href="dashboard.php" class="flex items-center p-3 rounded-xl bg-blue-600 shadow-lg text-white transition">
                <span class="mr-3">📊</span> Dashboard
            </a>
            <button onclick="toggleModal()" class="w-full flex items-center p-3 rounded-xl hover:bg-slate-800 text-slate-300 transition">
                <span class="mr-3">🛎️</span> New Booking
            </button>
            <a href="manage_rooms.php" class="flex items-center p-3 rounded-xl hover:bg-slate-800 text-slate-300 transition">
                <span class="mr-3">🏨</span> Manage Rooms
            </a>
            <a href="#" class="flex items-center p-3 rounded-xl hover:bg-slate-800 text-slate-300 transition">
                <span class="mr-3">🧹</span> Housekeeping
            </a>
        </nav>

        <div class="absolute bottom-10 left-6 right-6">
            <div class="bg-slate-800/50 p-4 rounded-2xl mb-4 border border-slate-700">
                <p class="text-[10px] text-slate-500 uppercase font-bold">Receptionist</p>
                <p class="text-sm font-bold text-blue-200"><?php echo $_SESSION['username']; ?></p>
            </div>
            <a href="logout.php" class="block text-center p-3 rounded-xl bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition font-bold text-sm">Sign Out</a>
        </div>
    </div>

    <div class="ml-64 p-10 w-full">
        
        <div class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight">System Overview</h2>
                <p class="text-slate-500 font-medium">Manage arrivals, departures, and inventory.</p>
            </div>
            <div class="flex gap-4">
                <button onclick="toggleModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-black shadow-xl transition-all hover:-translate-y-1">
                    + NEW RESERVATION
                </button>
            </div>
        </div>

        <?php if($success): ?>
            <div class="bg-emerald-500 text-white px-6 py-4 rounded-2xl mb-8 shadow-lg flex justify-between animate-bounce">
                <span class="font-bold font-mono uppercase tracking-widest">✔ Booking Confirmed Successfully</span>
                <button onclick="this.parentElement.remove()">✕</button>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <p class="text-xs text-slate-400 font-black uppercase">Available Rooms</p>
                <?php $avail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM rooms WHERE housekeeping_status='Clean'")); ?>
                <p class="text-4xl font-black text-slate-800 mt-2"><?php echo $avail['count']; ?></p>
                <div class="mt-2 text-emerald-500 text-xs font-bold">Ready for Walk-ins</div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <p class="text-xs text-slate-400 font-black uppercase">Today's Check-ins</p>
                <?php $today = date('Y-m-d'); $arr = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM bookings WHERE check_in='$today'")); ?>
                <p class="text-4xl font-black text-slate-800 mt-2"><?php echo $arr['count']; ?></p>
                <div class="mt-2 text-blue-500 text-xs font-bold">Expected Arrivals</div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <p class="text-xs text-slate-400 font-black uppercase">In-House Guests</p>
                <?php $house = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM bookings WHERE booking_status='Checked-In'")); ?>
                <p class="text-4xl font-black text-slate-800 mt-2"><?php echo $house['count']; ?></p>
                <div class="mt-2 text-purple-500 text-xs font-bold">Currently Staying</div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <p class="text-xs text-slate-400 font-black uppercase">Needs Cleaning</p>
                <?php $dirty = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM rooms WHERE housekeeping_status='Dirty'")); ?>
                <p class="text-4xl font-black text-red-500 mt-2"><?php echo $dirty['count']; ?></p>
                <div class="mt-2 text-slate-400 text-xs font-bold font-mono italic">Housekeeping Alert</div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-white">
                <h3 class="font-black text-slate-800 text-xl tracking-tight uppercase">Recent Reservations</h3>
                <div class="flex gap-2">
                    <span class="h-3 w-3 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Live System Feed</span>
                </div>
            </div>
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black">
                    <tr>
                        <th class="p-6">Guest Identity</th>
                        <th class="p-6">Room Assigned</th>
                        <th class="p-6">Stay Dates</th>
                        <th class="p-6">Source</th>
                        <th class="p-6">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php
                    $query = "SELECT b.*, g.full_name, g.loyalty_status, r.room_number, r.room_type 
                              FROM bookings b 
                              JOIN guests g ON b.guest_id = g.guest_id 
                              JOIN rooms r ON b.room_id = r.room_id
                              ORDER BY b.booking_id DESC LIMIT 8";
                    $result = mysqli_query($conn, $query);
                    while($row = mysqli_fetch_assoc($result)):
                    ?>
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="p-6">
                            <div class="font-bold text-slate-900 text-lg"><?php echo $row['full_name']; ?></div>
                            <div class="flex items-center gap-1">
                                <span class="text-[10px] font-black px-2 py-0.5 rounded bg-blue-100 text-blue-600 uppercase">
                                    <?php echo $row['loyalty_status']; ?>
                                </span>
                            </div>
                        </td>
                        <td class="p-6 text-slate-700">
                            <div class="font-black">#<?php echo $row['room_number']; ?></div>
                            <div class="text-xs font-medium text-slate-400"><?php echo $row['room_type']; ?></div>
                        </td>
                        <td class="p-6">
                            <div class="text-sm font-bold text-slate-600"><?php echo date('D, M d', strtotime($row['check_in'])); ?></div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase">to <?php echo date('D, M d', strtotime($row['check_out'])); ?></div>
                        </td>
                        <td class="p-6">
                            <span class="text-[10px] font-black text-slate-500 border border-slate-200 px-3 py-1.5 rounded-lg">
                                <?php echo $row['booking_source']; ?>
                            </span>
                        </td>
                        <td class="p-6">
                            <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm
                                <?php echo $row['booking_status'] == 'Checked-In' ? 'bg-emerald-500 text-white' : 'bg-blue-500 text-white'; ?>">
                                <?php echo $row['booking_status']; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="bookingModal" class="hidden fixed inset-0 bg-slate-900/80 backdrop-blur-md flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-2xl p-10 transform transition-all border border-white/20">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight uppercase">Reservation Form</h3>
                    <p class="text-slate-400 text-sm font-medium">Capture guest details and check availability.</p>
                </div>
                <button onclick="toggleModal()" class="text-slate-300 hover:text-slate-900 transition text-4xl font-light">&times;</button>
            </div>

            <form action="process_booking.php" method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Guest Full Name</label>
                        <input type="text" name="full_name" required placeholder="John Doe" class="w-full mt-2 p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition-all font-bold">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">ID Type</label>
                        <select name="id_type" class="w-full mt-2 p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none font-bold">
                            <option>Passport</option>
                            <option>National ID</option>
                            <option>Drivers License</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">ID Number / Passport #</label>
                        <input type="text" name="id_number" required class="w-full mt-2 p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none font-bold">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Assign Room</label>
                        <select name="room_id" required class="w-full mt-2 p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none font-bold">
                            <?php 
                            // Only show rooms that are CLEAN and not currently occupied by a "Checked-In" guest
                            $rooms = mysqli_query($conn, "SELECT * FROM rooms WHERE housekeeping_status='Clean'");
                            while($r = mysqli_fetch_assoc($rooms)) {
                                $rid = $r['room_id'];
                                $occ_check = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM bookings WHERE room_id='$rid' AND booking_status='Checked-In'"));
                                if($occ_check == 0) {
                                    echo "<option value='".$r['room_id']."'>#".$r['room_number']." - ".$r['room_type']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Booking Source</label>
                        <select name="booking_source" class="w-full mt-2 p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none font-bold">
                            <option value="Walk-in">🧍 Walk-in</option>
                            <option value="Phone">📞 Phone Reservation</option>
                            <option value="Online">🌍 Online Booking</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Check-In Date</label>
                        <input type="date" name="check_in" value="<?php echo date('Y-m-d'); ?>" required class="w-full mt-2 p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none font-bold">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Check-Out Date</label>
                        <input type="date" name="check_out" required class="w-full mt-2 p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none font-bold">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" name="submit_booking" class="w-full bg-slate-900 text-white font-black py-5 rounded-[1.5rem] hover:bg-blue-600 transition-all shadow-2xl uppercase tracking-widest">
                        Complete Reservation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal() {
            const modal = document.getElementById('bookingModal');
            modal.classList.toggle('hidden');
        }
    </script>
</body>
</html>