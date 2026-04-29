<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: ../index.php");
require '../db.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$id = $_GET['id'];
$animal = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM animals WHERE id=$id"));

if(isset($_POST['health_update'])){
    $note = $_POST['note'];
    $date = $_POST['check_date'];
    mysqli_query($conn, "INSERT INTO health_records (animal_id, check_date, note) VALUES ($id, '$date', '$note')");
    mysqli_query($conn, "UPDATE animals SET last_check='$date', status='Checked' WHERE id=$id");
    header("Location: detail.php?id=$id");
    exit;
}
?>

<h2 class="text-3xl font-bold mb-4"><?= $animal['name'] ?> (<?= $animal['type'] ?>)</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-xl shadow">
        <h3 class="text-xl font-semibold mb-3">Details</h3>
        <?php if($animal['image_path'] && file_exists("../".$animal['image_path'])): ?>
            <img src="../<?= $animal['image_path'] ?>" class="w-full max-h-64 object-cover rounded mb-4">
        <?php endif; ?>
        <p>Age: <?= $animal['age'] ?> yrs</p>
        <p>Weight: <?= $animal['weight'] ?> kg</p>
        <p>Status: <?= $animal['status'] ?></p>
        <p>Last Health Check: <?= $animal['last_check'] ?: 'Never' ?></p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow">
        <h3 class="text-xl font-semibold mb-3">Update Health</h3>
        <form method="POST">
            <input type="date" name="check_date" required class="w-full border p-2 mb-2 rounded">
            <textarea name="note" placeholder="Vaccination, treatment, etc." class="w-full border p-2 mb-2 rounded" rows="2"></textarea>
            <button name="health_update" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
        </form>
    </div>
</div>
<?php include '../layout/footer.php'; ?>