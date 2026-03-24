<?php 
include('db.php'); 

if(!isset($_SESSION['staff_id'])) {
    header("Location: index.php");
    exit();
}


if($_SESSION['role'] !== 'Admin') {
    
    header("Location: dashboard.php?error=unauthorized");
    exit();
}

// --- UPDATED ADD ROOM LOGIC ---
if(isset($_POST['add_room'])){
    $num = mysqli_real_escape_string($conn, $_POST['room_num']);
    $type = $_POST['room_type'];
    $price = $_POST['price'];
    
    // NEW: Handle Description and Photo
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $photo_name = "";
    if(isset($_FILES['room_photo']) && $_FILES['room_photo']['error'] == 0){
        $target_dir = "uploads/";
        if(!is_dir($target_dir)) mkdir($target_dir);
        $photo_name = time() . "_" . basename($_FILES["room_photo"]["name"]);
        move_uploaded_file($_FILES["room_photo"]["tmp_name"], $target_dir . $photo_name);
    }
    
    $exists = mysqli_query($conn, "SELECT * FROM rooms WHERE room_number='$num'");
    if(mysqli_num_rows($exists) > 0) {
        echo "<script>alert('Room number already exists!');</script>";
    } else {
        // UPDATED: Insert statement now includes description and photo
        mysqli_query($conn, "INSERT INTO rooms (room_number, room_type, price_per_night, housekeeping_status, description, photo) 
                            VALUES ('$num', '$type', '$price', 'Clean', '$description', '$photo_name')");
        header("Location: manage_rooms.php?msg=added");
    }
    
}

// --- NEW EDIT ROOM LOGIC ---
if(isset($_POST['edit_room'])){
    $edit_id = mysqli_real_escape_string($conn, $_POST['edit_room_id']);
    $edit_num = mysqli_real_escape_string($conn, $_POST['edit_room_num']);
    $edit_type = $_POST['edit_room_type'];
    $edit_price = $_POST['edit_price'];
    $edit_description = mysqli_real_escape_string($conn, $_POST['edit_description']);
    
    $update_photo_query = "";
    if(isset($_FILES['edit_room_photo']) && $_FILES['edit_room_photo']['error'] == 0){
        $target_dir = "uploads/";
        if(!is_dir($target_dir)) mkdir($target_dir);
        $photo_name = time() . "_" . basename($_FILES["edit_room_photo"]["name"]);
        if(move_uploaded_file($_FILES["edit_room_photo"]["tmp_name"], $target_dir . $photo_name)) {
            $update_photo_query = ", photo='$photo_name'";
        }
    }

    mysqli_query($conn, "UPDATE rooms SET room_number='$edit_num', room_type='$edit_type', price_per_night='$edit_price', description='$edit_description' $update_photo_query WHERE room_id='$edit_id'");
    header("Location: manage_rooms.php?msg=edited");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Manage Inventory | Grand Hotel</title>
</head>
<body class="bg-slate-50 flex min-h-screen">

    <div class="w-64 bg-slate-900 text-white p-6 fixed h-full shadow-2xl">
        <div class="mb-10 text-center">
            <h1 class="text-2xl font-black text-blue-400 tracking-tighter">GRAND HOTEL</h1>
        </div>
        <nav class="space-y-2">
            <a href="dashboard.php" class="flex items-center p-3 rounded-xl hover:bg-slate-800 text-slate-300 transition">
                <span class="mr-3">📊</span> Dashboard
            </a>
            <a href="manage_rooms.php" class="flex items-center p-3 rounded-xl bg-blue-600 text-white shadow-lg">
                <span class="mr-3">🏨</span> Manage Rooms
            </a>
            <a href="logout.php" class="block text-red-400 mt-10 p-3 text-sm font-bold border border-red-900/30 rounded-lg text-center">Logout</a>
        </nav>
    </div>

    <div class="ml-64 p-10 w-full">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-black text-slate-800 tracking-tight uppercase">Room Inventory</h2>
            <div class="flex gap-4 text-xs font-bold uppercase tracking-widest text-slate-400">
                <span class="flex items-center"><span class="h-2 w-2 rounded-full bg-emerald-500 mr-2"></span> Available</span>
                <span class="flex items-center"><span class="h-2 w-2 rounded-full bg-red-500 mr-2"></span> Occupied</span>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 mb-10">
            <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-4">Add New Unit</h3>
            <form method="POST" enctype="multipart/form-data" class="grid grid-cols-4 gap-6 items-end">
                <div>
                    <label class="text-[10px] font-bold text-slate-500 ml-1">ROOM NUMBER</label>
                    <input type="text" name="room_num" required placeholder="e.g. 101" class="w-full mt-2 p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 ml-1">ROOM CATEGORY</label>
                    <select name="room_type" class="w-full mt-2 p-3 bg-slate-50 border border-slate-200 rounded-xl outline-none font-bold">
                        <option>Standard</option>
                        <option>Deluxe Suite</option>
                        <option>Executive King</option>
                        <option>Penthouse</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 ml-1">PRICE / NIGHT ($)</label>
                    <input type="number" name="price" required placeholder="150" class="w-full mt-2 p-3 bg-slate-50 border border-slate-200 rounded-xl outline-none font-bold">
                </div>

                <div class="col-span-2">
                    <label class="text-[10px] font-bold text-slate-500 ml-1">ROOM DESCRIPTION</label>
                    <input type="text" name="description" placeholder="Brief room details..." class="w-full mt-2 p-3 bg-slate-50 border border-slate-200 rounded-xl outline-none font-bold">
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-500 ml-1">ROOM PHOTO</label>
                    <input type="file" name="room_photo" accept="image/*" class="w-full mt-2 p-2.5 bg-slate-50 border border-slate-200 rounded-xl outline-none font-bold text-xs text-slate-500">
                </div>

                <button type="submit" name="add_room" class="bg-slate-900 text-white font-black py-3.5 rounded-xl hover:bg-blue-600 transition shadow-lg uppercase text-xs tracking-widest">
                    Add to Inventory
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            $res = mysqli_query($conn, "SELECT * FROM rooms ORDER BY room_number ASC");
            while($room = mysqli_fetch_assoc($res)):
                $r_id = $room['room_id'];
                
                $occ_res = mysqli_query($conn, "SELECT g.full_name FROM bookings b JOIN guests g ON b.guest_id = g.guest_id WHERE b.room_id='$r_id' AND b.booking_status='Checked-In' LIMIT 1");
                $occupied = mysqli_fetch_assoc($occ_res);
            ?>
            
            <div class="relative overflow-hidden bg-white p-6 rounded-[2rem] shadow-sm border-2 <?php echo $occupied ? 'border-red-100' : 'border-slate-50'; ?> transition hover:shadow-xl">
                
                <?php if(!empty($room['photo'])): ?>
                    <div class="h-32 -mx-6 -mt-6 mb-6 overflow-hidden">
                        <img src="uploads/<?php echo $room['photo']; ?>" class="w-full h-full object-cover">
                    </div>
                <?php endif; ?>

                <div class="flex justify-between items-start mb-4">
                    <div class="h-12 w-12 bg-slate-900 text-white flex items-center justify-center rounded-2xl font-black text-xl shadow-lg">
                        <?php echo $room['room_number']; ?>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="text-[10px] font-black px-3 py-1 rounded-full uppercase <?php echo $room['housekeeping_status'] == 'Clean' ? 'bg-emerald-100 text-emerald-600' : 'bg-yellow-100 text-yellow-600'; ?>">
                            <?php echo $room['housekeeping_status']; ?>
                        </span>
                    </div>
                </div>

                <div class="mb-6">
                    <p class="font-black text-slate-800 text-lg"><?php echo $room['room_type']; ?></p>
                    <p class="text-blue-600 font-bold text-sm">$<?php echo $room['price_per_night']; ?> <span class="text-slate-400 font-medium">/night</span></p>
                    
                    <p class="text-[11px] text-slate-400 mt-2 italic line-clamp-2">
                        <?php echo !empty($room['description']) ? $room['description'] : 'No description added.'; ?>
                    </p>
                </div>

                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                    <?php if($occupied): ?>
                        <p class="text-[10px] font-black text-red-500 uppercase tracking-widest">● CURRENTLY OCCUPIED</p>
                        <p class="text-sm font-bold text-slate-700 mt-1 truncate"><?php echo $occupied['full_name']; ?></p>
                    <?php else: ?>
                        <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">● VACANT & READY</p>
                        <p class="text-sm font-bold text-slate-400 mt-1">No Active Guest</p>
                    <?php endif; ?>
                </div>

                <div class="mt-4 flex gap-2">
                    <?php if($room['housekeeping_status'] == 'Dirty'): ?>
                        <a href="?mark_clean=<?php echo $r_id; ?>" class="w-full text-center bg-emerald-50 text-emerald-600 text-[10px] font-black py-2 rounded-lg hover:bg-emerald-500 hover:text-white transition uppercase">Mark Cleaned</a>
                    <?php endif; ?>
                   <button type="button" onclick="openEditModal('<?php echo $r_id; ?>', '<?php echo $room['room_number']; ?>', '<?php echo $room['room_type']; ?>', '<?php echo $room['price_per_night']; ?>', '<?php echo htmlspecialchars(isset($room['description']) ? $room['description'] : '', ENT_QUOTES); ?>')" class="flex-1 bg-slate-100 text-slate-500 text-[10px] font-black py-2 rounded-lg hover:bg-slate-200 transition uppercase">Edit</button>
                </div>
            </div> <?php endwhile; ?>
        </div>
    </div>

    <div id="editRoomModal" class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm z-[100] hidden items-center justify-center p-6">
        <div class="bg-white w-full max-w-xl rounded-[2rem] overflow-hidden shadow-2xl">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-2xl font-black uppercase tracking-tighter text-slate-800">Edit Room</h2>
                <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-900 text-2xl font-black transition">✕</button>
            </div>
            <form method="POST" enctype="multipart/form-data" class="p-8 space-y-4">
                <input type="hidden" name="edit_room_id" id="modal_edit_room_id">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Room Number</label>
                        <input type="text" name="edit_room_num" id="modal_edit_room_num" required class="w-full mt-2 p-3 bg-slate-50 border-2 border-slate-100 rounded-xl font-bold outline-none">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Room Category</label>
                        <select name="edit_room_type" id="modal_edit_room_type" class="w-full mt-2 p-3 bg-slate-50 border-2 border-slate-100 rounded-xl font-bold outline-none">
                            <option>Standard</option>
                            <option>Deluxe Suite</option>
                            <option>Executive King</option>
                            <option>Penthouse</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Price / Night ($)</label>
                    <input type="number" name="edit_price" id="modal_edit_price" required class="w-full mt-2 p-3 bg-slate-50 border-2 border-slate-100 rounded-xl font-bold outline-none">
                </div>

                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Description</label>
                    <textarea name="edit_description" id="modal_edit_description" rows="2" class="w-full mt-2 p-3 bg-slate-50 border-2 border-slate-100 rounded-xl font-bold outline-none"></textarea>
                </div>

                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Update Photo (Optional)</label>
                    <input type="file" name="edit_room_photo" accept="image/*" class="w-full mt-2 p-2 bg-slate-50 border-2 border-slate-100 rounded-xl font-bold text-xs text-slate-500 outline-none">
                </div>

                <button type="submit" name="edit_room" class="w-full bg-slate-900 text-white font-black py-4 rounded-xl hover:bg-blue-600 transition shadow-lg uppercase tracking-widest mt-4">Save Changes</button>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, num, type, price, desc) {
            document.getElementById('modal_edit_room_id').value = id;
            document.getElementById('modal_edit_room_num').value = num;
            document.getElementById('modal_edit_room_type').value = type;
            document.getElementById('modal_edit_price').value = price;
            document.getElementById('modal_edit_description').value = desc;
            
            const modal = document.getElementById('editRoomModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditModal() {
            const modal = document.getElementById('editRoomModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</body>
</html>