<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: ../index.php");
require '../db.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$animals = mysqli_query($conn, "SELECT id, name, type FROM animals");

if(isset($_POST['record'])){
    $animal_id = $_POST['animal_id'];
    $date = $_POST['date'];
    $quantity = $_POST['quantity'];
    mysqli_query($conn, "INSERT INTO milk_records (animal_id, date, quantity) VALUES ('$animal_id','$date','$quantity')");
    header("Location: index.php");
    exit;
}
?>

<h2 class="text-3xl font-bold mb-6">Record Milk Production</h2>
<form method="POST" class="bg-white p-6 rounded-xl shadow max-w-md">
    <select name="animal_id" required class="w-full border p-2 mb-3 rounded">
        <option value="">Select Animal</option>
        <?php while($a = mysqli_fetch_assoc($animals)): ?>
        <option value="<?= $a['id'] ?>"><?= $a['name'] ?> (<?= $a['type'] ?>)</option>
        <?php endwhile; ?>
    </select>
    <input type="date" name="date" required value="<?= date('Y-m-d') ?>" class="w-full border p-2 mb-3 rounded">
    <input type="number" step="0.1" name="quantity" placeholder="Liters" required class="w-full border p-2 mb-4 rounded">
    <button name="record" class="bg-emerald-600 text-white px-4 py-2 rounded">Record</button>
</form>
<?php include '../layout/footer.php'; ?>