<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: ../index.php");
require '../db.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$animals = mysqli_query($conn, "SELECT id, name FROM animals");

if(isset($_POST['log'])){
    $animal_id = $_POST['animal_id'];
    $date = $_POST['date'];
    $food_type = $_POST['food_type'];
    $qty = $_POST['quantity'];
    mysqli_query($conn, "INSERT INTO daily_feeding (animal_id, feed_date, food_type, quantity) VALUES ('$animal_id','$date','$food_type','$qty')");
    echo "<p class='text-green-600 mb-4'>Logged!</p>";
}
?>

<h2 class="text-3xl font-bold mb-4">Log Daily Feed Given</h2>
<form method="POST" class="bg-white p-6 rounded-xl shadow max-w-md mb-6">
    <select name="animal_id" required class="w-full border p-2 mb-3 rounded">
        <option value="">Select Animal</option>
        <?php while($a = mysqli_fetch_assoc($animals)): ?>
        <option value="<?= $a['id'] ?>"><?= $a['name'] ?></option>
        <?php endwhile; ?>
    </select>
    <input type="date" name="date" value="<?= date('Y-m-d') ?>" required class="w-full border p-2 mb-3 rounded">
    <select name="food_type" required class="w-full border p-2 mb-3 rounded">
        <option value="Grass">Grass (roughage)</option>
        <option value="Concentrate">Concentrate</option>
        <option value="Hay">Hay (roughage)</option>
        <option value="Silage">Silage (roughage)</option>
        <option value="Mineral Mix">Mineral Mix</option>
        <option value="Other">Other</option>
    </select>
    <input type="number" step="0.1" name="quantity" placeholder="Kg" required class="w-full border p-2 mb-4 rounded">
    <button name="log" class="bg-emerald-600 text-white px-4 py-2 rounded">Log Feed</button>
</form>

<?php
$logs = mysqli_query($conn, "SELECT df.*, a.name FROM daily_feeding df JOIN animals a ON df.animal_id = a.id ORDER BY df.feed_date DESC LIMIT 20");
if(mysqli_num_rows($logs) > 0):
?>
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100">
            <tr><th class="p-3">Animal</th><th class="p-3">Date</th><th class="p-3">Food</th><th class="p-3">Qty (kg)</th></tr>
        </thead>
        <tbody>
        <?php while($l = mysqli_fetch_assoc($logs)): ?>
            <tr class="border-t"><td class="p-3"><?= $l['name'] ?></td><td class="p-3"><?= $l['feed_date'] ?></td><td class="p-3"><?= $l['food_type'] ?></td><td class="p-3"><?= $l['quantity'] ?></td></tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
<div class="bg-white p-6 rounded-xl text-gray-500">No feeding logs yet.</div>
<?php endif; ?>
<?php include '../layout/footer.php'; ?>