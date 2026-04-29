<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: ../index.php");
require '../db.php';
include '../layout/header.php';
include '../layout/sidebar.php';

if(isset($_POST['add'])){
    $animal_id = $_POST['animal_id'];
    $date = $_POST['date'];
    $weight = $_POST['weight'];
    mysqli_query($conn, "INSERT INTO weight_records (animal_id, record_date, weight) VALUES ('$animal_id','$date','$weight')");
    mysqli_query($conn, "UPDATE animals SET weight=$weight WHERE id=$animal_id");
    echo "<p class='text-green-600'>Weight recorded.</p>";
}

$animals = mysqli_query($conn, "SELECT id, name FROM animals");
$weights = mysqli_query($conn, "SELECT wr.*, a.name FROM weight_records wr JOIN animals a ON wr.animal_id = a.id ORDER BY wr.record_date DESC");
?>

<h2 class="text-3xl font-bold mb-4">Weight Records</h2>
<form method="POST" class="bg-white p-6 rounded-xl shadow max-w-md mb-6">
    <select name="animal_id" required class="w-full border p-2 mb-3 rounded">
        <option value="">Select Animal</option>
        <?php while($a = mysqli_fetch_assoc($animals)): ?>
        <option value="<?= $a['id'] ?>"><?= $a['name'] ?></option>
        <?php endwhile; ?>
    </select>
    <input type="date" name="date" value="<?= date('Y-m-d') ?>" required class="w-full border p-2 mb-3 rounded">
    <input type="number" step="0.1" name="weight" placeholder="Weight (kg)" required class="w-full border p-2 mb-4 rounded">
    <button name="add" class="bg-emerald-600 text-white px-4 py-2 rounded">Record Weight</button>
</form>

<?php if(mysqli_num_rows($weights) > 0): ?>
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100">
            <tr><th class="p-3">Animal</th><th class="p-3">Date</th><th class="p-3">Weight</th></tr>
        </thead>
        <tbody>
        <?php while($w = mysqli_fetch_assoc($weights)): ?>
            <tr class="border-t"><td class="p-3"><?= $w['name'] ?></td><td class="p-3"><?= $w['record_date'] ?></td><td class="p-3"><?= $w['weight'] ?> kg</td></tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
<?php include '../layout/footer.php'; ?>