<?php
include('db.php');
if(!isset($_SESSION['staff_id'])) { header("Location: index.php"); exit(); }

if(isset($_GET['booking_id'])) {
    $b_id = mysqli_real_escape_string($conn, $_GET['booking_id']);
    
    // Fetch booking details to calculate bill
    $query = "SELECT b.*, r.price_per_night, r.room_id FROM bookings b 
              JOIN rooms r ON b.room_id = r.room_id WHERE b.booking_id = '$b_id'";
    $res = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($res);

    $days = (strtotime($data['check_out']) - strtotime($data['check_in'])) / 86400;
    $total_bill = $days * $data['price_per_night'];

    if(isset($_POST['confirm_checkout'])) {
        // Update booking status and mark room as Dirty for housekeeping
        mysqli_query($conn, "UPDATE bookings SET booking_status = 'Checked-Out' WHERE booking_id = '$b_id'");
        mysqli_query($conn, "UPDATE rooms SET housekeeping_status = 'Dirty' WHERE room_id = '{$data['room_id']}'");
        header("Location: dashboard.php?msg=checked_out");
    }
}
?>
<div class="p-10 bg-white rounded-xl shadow-lg max-w-lg mx-auto mt-20">
    <h2 class="text-2xl font-bold mb-4">Finalize Checkout</h2>
    <p>Guest: <?php echo $data['booking_id']; ?></p>
    <p class="text-3xl font-black text-blue-600 my-4">Total Due: $<?php echo number_format($total_bill, 2); ?></p>
    <form method="POST">
        <button name="confirm_checkout" class="w-full bg-slate-900 text-white py-3 rounded-lg">Confirm Payment & Checkout</button>
    </form>
</div>