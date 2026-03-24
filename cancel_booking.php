<?php
include('db.php');
session_start();

// Check if there is an active booking in the session
if (isset($_SESSION['user_booking_id'])) {
    $booking_id = $_SESSION['user_booking_id'];

    // 1. Delete the booking from the database
    // Note: Depending on your DB settings, you may need to delete 
    // related guest info first if you don't use CASCADE deletes.
    $delete_query = "DELETE FROM bookings WHERE booking_id = '$booking_id'";
    
    if (mysqli_query($conn, $delete_query)) {
        // 2. Clear the session variable so the dashboard resets
        unset($_SESSION['user_booking_id']);
    }
}

// 3. Redirect back to the booking dashboard
header("Location: book_online.php");
exit();
?>