<?php
include('db.php');


$current_booking_id = isset($_SESSION['user_booking_id']) ? $_SESSION['user_booking_id'] : null;

$booking = null;
if ($current_booking_id) {
    // EXTENDED: Added r.description and r.photo to the select query
    $query = "SELECT b.*, g.full_name, r.room_number, r.room_type, r.price_per_night, r.description, r.photo 
              FROM bookings b 
              JOIN guests g ON b.guest_id = g.guest_id 
              JOIN rooms r ON b.room_id = r.room_id 
              WHERE b.booking_id = '$current_booking_id'";
    $res = mysqli_query($conn, $query);
    $booking = mysqli_fetch_assoc($res);
}


$rooms_result = mysqli_query($conn, "SELECT * FROM rooms");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Grand Hotel | Luxury Stay</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900">

    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50 px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-extrabold tracking-tighter text-slate-900 italic">GRAND<span class="text-blue-600">HOTEL</span></h1>
            <div class="space-x-8 text-sm font-bold uppercase tracking-widest text-slate-500">
                <a href="#rooms" class="hover:text-blue-600 transition">Rooms</a>
                <a href="#amenities" class="hover:text-blue-600 transition">Amenities</a>
                <a href="find_booking.php" class="hover:text-blue-600 transition border-l pl-8 border-slate-200">Find My Booking</a>
                <?php if($booking): ?>
                    <span class="bg-blue-100 text-blue-600 px-4 py-2 rounded-full text-[10px]">My Reservation Active</span>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <?php if ($booking): ?>
        <main class="max-w-4xl mx-auto py-16 px-6">
            <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden border border-slate-100">
                <div class="bg-slate-900 p-12 text-center text-white">
                    <div class="w-16 h-16 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6 text-2xl">✓</div>
                    <h2 class="text-4xl font-black mb-2">Booking Confirmed!</h2>
                    <p class="text-slate-400 font-medium">We're excited to welcome you, <?php echo explode(' ', $booking['full_name'])[0]; ?>.</p>
                </div>
                
                <div class="p-12">
                    <div class="grid md:grid-cols-2 gap-12">
                        <div>
                            <h3 class="text-xs font-black uppercase tracking-[0.2em] text-blue-600 mb-4">Stay Details</h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-slate-400 font-bold">Room</p>
                                    <p class="text-xl font-black"><?php echo $booking['room_type']; ?> (Room <?php echo $booking['room_number']; ?>)</p>
                                </div>
                                <div class="flex gap-8">
                                    <div>
                                        <p class="text-sm text-slate-400 font-bold">Check-in</p>
                                        <p class="font-black"><?php echo date('M d, Y', strtotime($booking['check_in'])); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-slate-400 font-bold">Check-out</p>
                                        <p class="font-black"><?php echo date('M d, Y', strtotime($booking['check_out'])); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 p-8 rounded-[2rem]">
                            <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Description</h3>
                            <p class="text-sm text-slate-600 leading-relaxed italic">
                                "<?php echo !empty($booking['description']) ? $booking['description'] : 'A sanctuary of comfort. Your room includes premium linens, high-speed Wi-Fi, and access to all hotel amenities.'; ?>"
                            </p>
                            <div class="mt-6 pt-6 border-t border-slate-200">
                                <p class="text-xs font-bold text-slate-400 uppercase">Reference Number</p>
                                <p class="text-lg font-black text-slate-900">#BK-<?php echo $booking['booking_id']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-12 flex gap-4">
                        <button onclick="window.print()" class="flex-1 bg-slate-900 text-white py-5 rounded-2xl font-black uppercase tracking-widest hover:bg-blue-600 transition shadow-lg">Print Confirmation</button>
                        <a href="cancel_booking.php" class="flex-1 bg-slate-100 text-slate-500 py-5 rounded-2xl font-black uppercase tracking-widest text-center hover:bg-red-50 hover:text-red-500 transition">Cancel Booking</a>
                    </div>
                </div>
            </div>
        </main>

    <?php else: ?>
        <header class="bg-slate-900 py-24 px-6 text-center text-white">
            <h2 class="text-6xl font-black tracking-tighter mb-4">Your Journey Begins Here</h2>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">Discover a new level of luxury and comfort in the heart of the city.</p>
        </header>

        <section id="amenities" class="max-w-7xl mx-auto py-20 px-6">
            <h3 class="text-2xl font-black mb-10 border-l-4 border-blue-600 pl-4 uppercase tracking-tighter">Premium Amenities</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white p-8 rounded-3xl border border-slate-100 text-center hover:border-blue-600 transition">
                    <div class="text-3xl mb-4">📶</div>
                    <h4 class="font-black text-xs uppercase tracking-widest">Free Wi-Fi</h4>
                    <p class="text-slate-400 text-[10px] mt-2 font-bold">High Speed Connectivity</p>
                </div>
                <div class="bg-white p-8 rounded-3xl border border-slate-100 text-center hover:border-blue-600 transition">
                    <div class="text-3xl mb-4">🍳</div>
                    <h4 class="font-black text-xs uppercase tracking-widest">Breakfast</h4>
                    <p class="text-slate-400 text-[10px] mt-2 font-bold">7:00 AM - 10:00 AM</p>
                </div>
                <div class="bg-white p-8 rounded-3xl border border-slate-100 text-center hover:border-blue-600 transition">
                    <div class="text-3xl mb-4">🏋️</div>
                    <h4 class="font-black text-xs uppercase tracking-widest">Fitness Center</h4>
                    <p class="text-slate-400 text-[10px] mt-2 font-bold">Open 24/7</p>
                </div>
                <div class="bg-white p-8 rounded-3xl border border-slate-100 text-center hover:border-blue-600 transition">
                    <div class="text-3xl mb-4">❄️</div>
                    <h4 class="font-black text-xs uppercase tracking-widest">Smart AC</h4>
                    <p class="text-slate-400 text-[10px] mt-2 font-bold">Individual Control</p>
                </div>
            </div>
        </section>

        <main id="rooms" class="max-w-7xl mx-auto py-20 px-6">
            <h3 class="text-2xl font-black mb-10 border-l-4 border-blue-600 pl-4 uppercase tracking-tighter">Available Accommodations</h3>
            
            <div class="grid md:grid-cols-3 gap-8">
                <?php while($room = mysqli_fetch_assoc($rooms_result)): ?>
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden group hover:shadow-2xl transition-all duration-500">
                        <div class="h-48 bg-slate-200 relative overflow-hidden">
                            <?php if(!empty($room['photo'])): ?>
                                <img src="uploads/<?php echo $room['photo']; ?>" class="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-110">
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <span class="absolute top-4 left-4 bg-white/20 backdrop-blur-md text-white text-[10px] font-black px-3 py-1 rounded-full uppercase">Room <?php echo $room['room_number']; ?></span>
                        </div>
                        <div class="p-8">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="text-xl font-black"><?php echo $room['room_type']; ?></h4>
                                <p class="text-blue-600 font-black text-xl">$<?php echo $room['price_per_night']; ?><span class="text-xs text-slate-400 font-bold italic">/night</span></p>
                            </div>
                            <p class="text-slate-500 text-sm leading-relaxed mb-6 line-clamp-3">
                                <?php echo !empty($room['description']) ? $room['description'] : "Featuring modern decor, a king-sized bed, and panoramic windows. Perfect for business or leisure."; ?>
                            </p>
                            <div class="flex gap-4 mb-8">
                                <span class="text-[10px] font-bold bg-slate-100 px-3 py-1 rounded-md text-slate-500 italic">WiFi</span>
                                <span class="text-[10px] font-bold bg-slate-100 px-3 py-1 rounded-md text-slate-500 italic">AC</span>
                                <span class="text-[10px] font-bold bg-slate-100 px-3 py-1 rounded-md text-slate-500 italic">Smart TV</span>
                            </div>

                            <?php if($room['housekeeping_status'] == 'Clean'): ?>
                                <button onclick="openBooking('<?php echo $room['room_id']; ?>', '<?php echo $room['room_type']; ?>')" class="w-full bg-slate-900 text-white font-black py-4 rounded-xl uppercase tracking-widest text-xs group-hover:bg-blue-600 transition">Book Now</button>
                            <?php else: ?>
                                <button disabled class="w-full bg-slate-100 text-slate-400 font-black py-4 rounded-xl uppercase tracking-widest text-xs cursor-not-allowed italic">Currently Unavailable</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </main>

        <div id="bookingModal" class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm z-[100] hidden items-center justify-center p-6">
            <div class="bg-white w-full max-w-xl rounded-[3rem] overflow-hidden shadow-2xl">
                <div class="p-10 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-black uppercase tracking-tighter">Complete Reservation</h2>
                        <p id="selectedRoomName" class="text-blue-600 font-bold text-sm italic"></p>
                    </div>
                    <button onclick="closeBooking()" class="text-slate-300 hover:text-slate-900 text-2xl font-black">✕</button>
                </div>
                <form action="process_booking.php" method="POST" class="p-10 space-y-6">
                    <input type="hidden" name="booking_source" value="Online">
                    <input type="hidden" name="is_public" value="1">
                    <input type="hidden" name="room_id" id="modalRoomId">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Your Full Name</label>
                            <input type="text" name="full_name" required class="w-full mt-2 p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-blue-500 outline-none font-bold">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Check-In</label>
                            <input type="date" name="check_in" id="check_in" min="<?php echo date('Y-m-d'); ?>" required class="w-full mt-2 p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl font-bold">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Check-Out</label>
                            <input type="date" name="check_out" id="check_out" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required class="w-full mt-2 p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl font-bold">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">ID Type</label>
                            <select name="id_type" class="w-full mt-2 p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl font-bold">
                                <option>Passport</option>
                                <option>National ID</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">ID Number</label>
                            <input type="text" name="id_number" required class="w-full mt-2 p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl font-bold">
                        </div>
                    </div>

                    <button type="submit" name="submit_booking" class="w-full bg-blue-600 text-white font-black py-5 rounded-2xl hover:bg-slate-900 transition-all shadow-xl uppercase tracking-widest mt-4">Confirm & Reserve</button>
                </form>
            </div>
        </div>

        <script>
            // Modal Controls
            function openBooking(id, name) {
                document.getElementById('modalRoomId').value = id;
                document.getElementById('selectedRoomName').innerText = "Selected: " + name;
                document.getElementById('bookingModal').classList.remove('hidden');
                document.getElementById('bookingModal').classList.add('flex');
            }
            function closeBooking() {
                document.getElementById('bookingModal').classList.add('hidden');
                document.getElementById('bookingModal').classList.remove('flex');
            }

            // Date Validation Logic
            const checkInInput = document.getElementById('check_in');
            const checkOutInput = document.getElementById('check_out');

            checkInInput.addEventListener('change', function() {
                if (this.value) {
                    let selectedDate = new Date(this.value);
                    let nextDay = new Date(selectedDate);
                    nextDay.setDate(selectedDate.getDate() + 1);
                    
                    let minOutDate = nextDay.toISOString().split('T')[0];
                    checkOutInput.min = minOutDate;
                    
                    if (checkOutInput.value && checkOutInput.value < minOutDate) {
                        checkOutInput.value = minOutDate;
                    }
                }
            });
        </script>
    <?php endif; ?>

    <footer class="bg-slate-900 py-10 text-center">
        <p class="text-slate-600 text-[10px] font-bold uppercase tracking-[0.3em]">&copy; 2024 Grand Hotel International</p>
    </footer>

</body>
</html>