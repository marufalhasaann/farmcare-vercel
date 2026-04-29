<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: ../index.php");
require '../db.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$animals = mysqli_query($conn, "SELECT id, name, weight FROM animals");
$prediction = '';

if(isset($_POST['predict'])){
    $animal_id = $_POST['animal_id'];
    $days = $_POST['days'];
    $daily_feed_kg = $_POST['daily_feed'];
    $fcr = $_POST['fcr'] ?? 8;

    $cur = mysqli_fetch_assoc(mysqli_query($conn, "SELECT weight FROM animals WHERE id=$animal_id"));
    $current_weight = $cur['weight'];
    $daily_gain = $daily_feed_kg / $fcr;
    $future_weight = $current_weight + ($daily_gain * $days);
    $prediction = "Current weight: {$current_weight} kg<br> After {$days} days, expected weight: <b>" . round($future_weight,1) . " kg</b> (gain: " . round($daily_gain*$days,1) . " kg)";
}
?>

<h2 class="text-3xl font-bold mb-4">Weight Gain Predictor</h2>
<form method="POST" class="bg-white p-6 rounded-xl shadow max-w-md">
    <select name="animal_id" required class="w-full border p-2 mb-3 rounded">
        <option value="">Select Animal</option>
        <?php while($a = mysqli_fetch_assoc($animals)): ?>
        <option value="<?= $a['id'] ?>"><?= $a['name'] ?> (<?= $a['weight'] ?> kg)</option>
        <?php endwhile; ?>
    </select>
    <label>Daily Feed (kg)</label>
    <input type="number" step="0.1" name="daily_feed" value="10" required class="w-full border p-2 mb-3 rounded">
    <label>Feed Conversion Ratio (FCR)</label>
    <input type="number" step="0.1" name="fcr" value="8" required class="w-full border p-2 mb-3 rounded">
    <label>Days to Predict</label>
    <input type="number" name="days" value="30" required class="w-full border p-2 mb-4 rounded">
    <button name="predict" class="bg-emerald-600 text-white px-4 py-2 rounded">Predict</button>
</form>
<?php if($prediction): ?>
<div class="bg-white p-4 rounded-xl shadow mt-4"><?= $prediction ?></div>
<?php endif; ?>
<?php include '../layout/footer.php'; ?>