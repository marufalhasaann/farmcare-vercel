<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: ../index.php");
require '../db.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$animals = mysqli_query($conn, "SELECT id, name FROM animals");

if(isset($_POST['add'])){
    $animal_id = $_POST['animal_id'];
    $day = $_POST['day'];
    $time = $_POST['time'];
    $food_type = $_POST['food_type'];
    $quantity = $_POST['quantity'];
    mysqli_query($conn, "INSERT INTO feeding_schedule (animal_id, day_of_week, time, food_type, quantity)
                         VALUES ('$animal_id', '$day', '$time', '$food_type', '$quantity')");
    header("Location: index.php");
    exit;
}
?>
<h2 class="text-3xl font-bold mb-6">Add Feeding Schedule</h2>
<form method="POST" class="bg-white p-6 rounded-xl shadow max-w-md">
    <select name="animal_id" required class="w-full border p-2 mb-3 rounded">
        <option value="">Select Animal</option>
        <?php while($a = mysqli_fetch_assoc($animals)): ?>
        <option value="<?= $a['id'] ?>"><?= $a['name'] ?></option>
        <?php endwhile; ?>
    </select>
    <select name="day" required class="w-full border p-2 mb-3 rounded">
        <option value="Mon">Monday</option><option value="Tue">Tuesday</option><option value="Wed">Wednesday</option>
        <option value="Thu">Thursday</option><option value="Fri">Friday</option><option value="Sat">Saturday</option><option value="Sun">Sunday</option>
    </select>
    <input type="time" name="time" required class="w-full border p-2 mb-3 rounded">
    <input type="text" name="food_type" placeholder="Food type (e.g., Grass)" required class="w-full border p-2 mb-3 rounded">
    <input type="number" step="0.1" name="quantity" placeholder="Quantity (kg)" required class="w-full border p-2 mb-4 rounded">
    <button name="add" class="bg-emerald-600 text-white px-4 py-2 rounded">Add Schedule</button>
</form>
<?php include '../layout/footer.php'; ?>