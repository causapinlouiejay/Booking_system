<?php
include('db.php');


if(isset($_POST['submit_booking'])){
    
    $room_id = mysqli_real_escape_string($conn, $_POST['room_id']);
    $check_in = mysqli_real_escape_string($conn, $_POST['check_in']);
    $check_out = mysqli_real_escape_string($conn, $_POST['check_out']);
    $name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $id_type = mysqli_real_escape_string($conn, $_POST['id_type']);
    $id_num = mysqli_real_escape_string($conn, $_POST['id_number']);
    $source = mysqli_real_escape_string($conn, $_POST['booking_source']);
    
    
    $is_public = isset($_POST['is_public']);
    $redirect_to = $is_public ? "book_online.php" : "dashboard.php";
    
    
    if (isset($_SESSION['staff_id'])) {
    $staff_id = "'" . $_SESSION['staff_id'] . "'";
} else {
    $staff_id = "NULL"; 
}
    if (strtotime($check_out) <= strtotime($check_in)) {
        header("Location: $redirect_to?error=invalid_dates");
        exit;
    }

    $check_query = "SELECT * FROM bookings 
                    WHERE room_id = '$room_id' 
                    AND booking_status != 'Checked-Out'
                    AND (
                        ('$check_in' BETWEEN check_in AND check_out) OR 
                        ('$check_out' BETWEEN check_in AND check_out) OR
                        (check_in BETWEEN '$check_in' AND '$check_out')
                    )";
    
    $conflict = mysqli_query($conn, $check_query);

    if(mysqli_num_rows($conflict) > 0){
        header("Location: $redirect_to?status=conflict");
        exit();
    } else {
        // Insert Guest
       // REPLACING: mysqli_query($conn, "INSERT INTO guests...");
$check_guest = mysqli_query($conn, "SELECT guest_id FROM guests WHERE id_number = '$id_num'");

if(mysqli_num_rows($check_guest) > 0) {
    $g_data = mysqli_fetch_assoc($check_guest);
    $guest_id = $g_data['guest_id']; // Use existing guest
} else {
    mysqli_query($conn, "INSERT INTO guests (full_name, id_type, id_number) VALUES ('$name', '$id_type', '$id_num')");
    $guest_id = mysqli_insert_id($conn); // Use new guest
}
        $status = ($source == 'Walk-in') ? 'Checked-In' : 'Reserved';

        $sql = "INSERT INTO bookings (guest_id, room_id, staff_id, check_in, check_out, booking_source, booking_status) 
                VALUES ('$guest_id', '$room_id', $staff_id, '$check_in', '$check_out', '$source', '$status')";
        
        if(mysqli_query($conn, $sql)){
            $new_booking_id = mysqli_insert_id($conn);
            
            if($status == 'Checked-In'){
                mysqli_query($conn, "UPDATE rooms SET housekeeping_status='Dirty' WHERE room_id='$room_id'");
            }

            // Lock the user to this booking for their session
            if($is_public) {
                $_SESSION['user_booking_id'] = $new_booking_id;
            }

            header("Location: $redirect_to?status=success");
            exit();
        }
    }
}
?>