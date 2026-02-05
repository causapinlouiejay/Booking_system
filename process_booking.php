<?php
include('db.php');

if(isset($_POST['submit_booking'])){
    $room_id = $_POST['room_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    
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
        
        echo "<script>alert('Error: This room is already reserved for the selected dates!'); window.location='dashboard.php';</script>";
    } else {
        
        $name = $_POST['full_name'];
        $id_type = $_POST['id_type'];
        $id_num = $_POST['id_number'];
        $source = $_POST['booking_source'];
        $staff_id = $_SESSION['staff_id'];

        mysqli_query($conn, "INSERT INTO guests (full_name, id_type, id_number) VALUES ('$name', '$id_type', '$id_num')");
        $guest_id = mysqli_insert_id($conn);
        $status = ($source == 'Walk-in') ? 'Checked-In' : 'Reserved';

        $sql = "INSERT INTO bookings (guest_id, room_id, staff_id, check_in, check_out, booking_source, booking_status) 
                VALUES ('$guest_id', '$room_id', '$staff_id', '$check_in', '$check_out', '$source', '$status')";
        
        if(mysqli_query($conn, $sql)){
            if($status == 'Checked-In'){
                mysqli_query($conn, "UPDATE rooms SET housekeeping_status='Dirty' WHERE room_id='$room_id'");
            }
            header("Location: dashboard.php?success=1");
        }
    }
}
?>