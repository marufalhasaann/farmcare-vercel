<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: ../index.php");
include '../layout/header.php';
include '../layout/sidebar.php';

$result = '';
if(isset($_POST['calc'])){
    $type = $_POST['type'];
    $weight = $_POST['weight'];
    if($type == 'Cow'){
        $feed = $weight * 0.03;
        $water = $weight * 0.1;
    } else {
        $feed = $weight * 0.04;
        $water = $weight * 0.08;
    }
    $result = "Daily feed: <b>" . round($feed,2) . " kg</b><br>Daily water: <b>" . round($water,2) . " L</b>";
}
?>

<h2 class="text-3xl font-bold mb-6">Feed Calculator</h2>
<div class="bg-white p-6 rounded-xl shadow max-w-md">
    <form method="POST">
        <select name="type" class="w-full border p-2 mb-3 rounded">
            <option value="Cow">Cow</option>
            <option value="Goat">Goat</option>
        </select>
        <input type="number" name="weight" step="0.1" placeholder="Weight (kg)" required class="w-full border p-2 mb-4 rounded">
        <button name="calc" class="bg-emerald-600 text-white px-4 py-2 rounded">Calculate</button>
    </form>
    <?php if($result): ?>
    <div class="mt-4 p-3 bg-emerald-50 border border-emerald-200 rounded"><?= $result ?></div>
    <?php endif; ?>
</div>
<?php include '../layout/footer.php'; ?>