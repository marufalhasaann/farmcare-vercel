<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: ../index.php");
require '../db.php';
include '../layout/header.php';
include '../layout/sidebar.php';

if(isset($_GET['delete'])){
    mysqli_query($conn, "DELETE FROM feeding_schedule WHERE id=".$_GET['delete']);
    header("Location: index.php");
    exit;
}

$schedules = mysqli_query($conn, "SELECT fs.*, a.name FROM feeding_schedule fs JOIN animals a ON fs.animal_id = a.id ORDER BY FIELD(day_of_week,'Mon','Tue','Wed','Thu','Fri','Sat','Sun'), time");
?>
<h2 class="text-3xl font-bold mb-4">Feeding Schedules</h2>
<a href="add.php" class="inline-block bg-emerald-600 text-white px-4 py-2 rounded-lg mb-6">+ Add Schedule</a>
<?php if(mysqli_num_rows($schedules) > 0): ?>
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100">
            <tr><th class="p-3">Animal</th><th class="p-3">Day</th><th class="p-3">Time</th><th class="p-3">Food</th><th class="p-3">Qty</th><th class="p-3">Action</th></tr>
        </thead>
        <tbody>
        <?php while($s = mysqli_fetch_assoc($schedules)): ?>
            <tr class="border-t">
                <td class="p-3"><?= $s['name'] ?></td>
                <td class="p-3"><?= $s['day_of_week'] ?></td>
                <td class="p-3"><?= $s['time'] ?></td>
                <td class="p-3"><?= $s['food_type'] ?></td>
                <td class="p-3"><?= $s['quantity'] ?> kg</td>
                <td class="p-3"><a href="?delete=<?= $s['id'] ?>" class="text-red-600" onclick="return confirm('Delete?')">Delete</a></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
<div class="bg-white p-6 rounded-xl text-center text-gray-500">No schedules yet.</div>
<?php endif; ?>
<?php include '../layout/footer.php'; ?>