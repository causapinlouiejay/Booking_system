<?php 
include('db.php'); 
if(!isset($_SESSION['staff_id'])) { header("Location: index.php"); exit(); }

// Query to get guest info + aggregated stay data
$guest_query = "SELECT g.*, 
                COUNT(b.booking_id) as total_stays, 
                SUM(DATEDIFF(b.check_out, b.check_in) * r.price_per_night) as total_spent
                FROM guests g
                LEFT JOIN bookings b ON g.guest_id = b.guest_id
                LEFT JOIN rooms r ON b.room_id = r.room_id
                GROUP BY g.guest_id ORDER BY total_spent DESC";
$guests = mysqli_query($conn, $guest_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><script src="https://cdn.tailwindcss.com"></script>
    <title>Guest Management | Grand Hotel</title>
</head>
<body class="bg-slate-50 flex min-h-screen">
    <main class="flex-1 ml-64 p-12">
        <h2 class="text-3xl font-black text-slate-900 mb-8">Guest <span class="text-blue-600">Profiles</span></h2>
        <div class="bg-white rounded-[2rem] overflow-hidden shadow-sm border border-slate-100">
            <table class="w-full text-left">
                <thead class="bg-slate-900 text-white text-[10px] uppercase tracking-widest">
                    <tr>
                        <th class="p-6">Guest Name</th>
                        <th class="p-6">ID Details</th>
                        <th class="p-6 text-center">Total Stays</th>
                        <th class="p-6 text-right">Total Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php while($row = mysqli_fetch_assoc($guests)): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-6 font-bold text-slate-800"><?php echo $row['full_name']; ?></td>
                        <td class="p-6 text-xs text-slate-500"><?php echo $row['id_type']; ?>: <?php echo $row['id_number']; ?></td>
                        <td class="p-6 text-center font-black"><?php echo $row['total_stays']; ?></td>
                        <td class="p-6 text-right font-black text-emerald-600">$<?php echo number_format($row['total_spent'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>