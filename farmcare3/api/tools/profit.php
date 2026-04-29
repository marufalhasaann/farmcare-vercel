<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: ../index.php");
include '../layout/header.php';
include '../layout/sidebar.php';

if(isset($_POST['calc'])){
    $feed_cost = $_POST['feed_cost'];
    $med_cost = $_POST['med_cost'];
    $milk_sold = $_POST['milk_sold'];
    $milk_price = $_POST['milk_price'];
    $revenue = $milk_sold * $milk_price;
    $total_cost = $feed_cost + $med_cost;
    $profit = $revenue - $total_cost;
}
?>

<h2 class="text-3xl font-bold mb-6">Profit / Loss Calculator</h2>
<div class="bg-white p-6 rounded-xl shadow max-w-md">
    <form method="POST">
        <input type="number" name="feed_cost" step="0.01" placeholder="Feed Cost" required class="w-full border p-2 mb-3 rounded">
        <input type="number" name="med_cost" step="0.01" placeholder="Medicine Cost" required class="w-full border p-2 mb-3 rounded">
        <input type="number" name="milk_sold" step="0.1" placeholder="Milk Sold (liters)" required class="w-full border p-2 mb-3 rounded">
        <input type="number" name="milk_price" step="0.01" placeholder="Price per Liter" required class="w-full border p-2 mb-4 rounded">
        <button name="calc" class="bg-emerald-600 text-white px-4 py-2 rounded">Calculate</button>
    </form>
    <?php if(isset($profit)): ?>
    <div class="mt-4 p-3 rounded <?= $profit>=0 ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' ?>">
        <p>Revenue: $<?= number_format($revenue,2) ?></p>
        <p>Total Cost: $<?= number_format($total_cost,2) ?></p>
        <p class="font-bold text-lg">Profit/Loss: $<?= number_format($profit,2) ?></p>
    </div>
    <?php endif; ?>
</div>
<?php include '../layout/footer.php'; ?>