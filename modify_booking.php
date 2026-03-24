<?php
include('db.php');
$b_id = mysqli_real_escape_string($conn, $_GET['booking_id']);

if(isset($_POST['update_dates'])) {
    $new_in = $_POST['check_in'];
    $new_out = $_POST['check_out'];

    // Availability Logic: Check for overlaps excluding THIS booking
    $check = "SELECT * FROM bookings WHERE room_id = (SELECT room_id FROM bookings WHERE booking_id = '$b_id') 
              AND booking_id != '$b_id' 
              AND ('$new_in' < check_out AND '$new_out' > check_in)";
    
    if(mysqli_num_rows(mysqli_query($conn, $check)) == 0) {
        mysqli_query($conn, "UPDATE bookings SET check_in = '$new_in', check_out = '$new_out' WHERE booking_id = '$b_id'");
        header("Location: dashboard.php?msg=updated");
    } else {
        echo "<script>alert('Dates conflict with another booking!');</script>";
    }
}
?>
<form method="POST" class="max-w-md mx-auto mt-20 p-10 bg-white rounded-3xl shadow-xl">
    <h3 class="text-xl font-black mb-6 uppercase tracking-tighter">Modify Stay Dates</h3>
    <input type="date" name="check_in" required class="w-full mb-4 p-4 bg-slate-50 rounded-xl">
    <input type="date" name="check_out" required class="w-full mb-6 p-4 bg-slate-50 rounded-xl">
    <button name="update_dates" class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold">Update Reservation</button>
</form>