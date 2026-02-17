<?php
include('db.php');
session_start();

if(isset($_POST['submit_booking'])){
    // Sanitize inputs
    $room_id = mysqli_real_escape_string($conn, $_POST['room_id']);
    $check_in = mysqli_real_escape_string($conn, $_POST['check_in']);
    $check_out = mysqli_real_escape_string($conn, $_POST['check_out']);
    $name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $id_type = mysqli_real_escape_string($conn, $_POST['id_type']);
    $id_num = mysqli_real_escape_string($conn, $_POST['id_number']);
    $source = mysqli_real_escape_string($conn, $_POST['booking_source']);
    $staff_id = $_SESSION['staff_id'];

    
if (strtotime($check_out) <= strtotime($check_in)) {
    header("Location: dashboard.php?error=invalid_dates");
    exit;
}
// --- END OF NEW VALIDATION ---
    }

    // 2. CONFLICT CHECK: Is the room available?
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
        header("Location: dashboard.php?status=conflict");
        exit();
    } else {
        // 3. EXECUTE BOOKING
        mysqli_query($conn, "INSERT INTO guests (full_name, id_type, id_number) VALUES ('$name', '$id_type', '$id_num')");
        $guest_id = mysqli_insert_id($conn);
        
        $status = ($source == 'Walk-in') ? 'Checked-In' : 'Reserved';

        $sql = "INSERT INTO bookings (guest_id, room_id, staff_id, check_in, check_out, booking_source, booking_status) 
                VALUES ('$guest_id', '$room_id', '$staff_id', '$check_in', '$check_out', '$source', '$status')";
        
        if(mysqli_query($conn, $sql)){
            if($status == 'Checked-In'){
                mysqli_query($conn, "UPDATE rooms SET housekeeping_status='Dirty' WHERE room_id='$room_id'");
            }
            header("Location: dashboard.php?status=success");
            exit();
        }
    }

?>