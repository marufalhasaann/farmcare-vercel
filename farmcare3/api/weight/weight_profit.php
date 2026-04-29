<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: ../index.php");
require '../db.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$animals = mysqli_query($conn, "SELECT id, name FROM animals");
$profit_result = '';

if(isset($_POST['calculate'])){
    $animal_id = $_POST['animal_id'];
    $purchase_weight = $_POST['purchase_weight'];
    $purchase_price = $_POST['purchase_price'];
    $sale_weight = $_POST['sale_weight'];
    $sale_price_per_kg = $_POST['sale_price_per_kg'];
    $feed_cost_total = $_POST['feed_cost_total'];
    $other_cost = $_POST['other_cost'];

    $revenue = $sale_weight * $sale_price_per_kg;
    $total_cost = $purchase_price + $feed_cost_total + $other_cost;
    $profit = $revenue - $total_cost;
    $profit_result = "Revenue: $".number_format($revenue,2)."<br>Total Cost: $".number_format($total_cost,2)."<br><b>Profit: $".number_format($profit,2)."</b>";
}
?>

<h2 class="text-3xl font-bold mb-4">Weight Profit Calculator</h2>
<form method="POST" class="bg-white p-6 rounded-xl shadow max-w-lg mb-4">
    <select name="animal_id" required class="w-full border p-2 mb-3 rounded">
        <option value="">Select Animal</option>
        <?php while($a = mysqli_fetch_assoc($animals)): ?>
        <option value="<?= $a['id'] ?>"><?= $a['name'] ?></option>
        <?php endwhile; ?>
    </select>
    <input type="number" step="0.1" name="purchase_weight" placeholder="Purchase Weight (kg)" required class="w-full border p-2 mb-3 rounded">
    <input type="number" step="0.01" name="purchase_price" placeholder="Purchase Price (total)" required class="w-full border p-2 mb-3 rounded">
    <input type="number" step="0.1" name="sale_weight" placeholder="Sale Weight (kg)" required class="w-full border p-2 mb-3 rounded">
    <input type="number" step="0.01" name="sale_price_per_kg" placeholder="Sale Price per kg" required class="w-full border p-2 mb-3 rounded">
    <input type="number" step="0.01" name="feed_cost_total" placeholder="Total Feed Cost" required class="w-full border p-2 mb-3 rounded">
    <input type="number" step="0.01" name="other_cost" placeholder="Other Costs" required class="w-full border p-2 mb-4 rounded">
    <button name="calculate" class="bg-emerald-600 text-white px-4 py-2 rounded">Calculate Profit</button>
</form>
<?php if($profit_result): ?>
<div class="bg-white p-4 rounded-xl shadow"><?= $profit_result ?></div>
<?php endif; ?>
<?php include '../layout/footer.php'; ?>