<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: ../index.php");
require '../db.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$roughage = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) AS total FROM daily_feeding WHERE food_type IN ('Grass','Hay','Silage') AND feed_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)"))['total'] ?? 0;
$concentrate = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) AS total FROM daily_feeding WHERE food_type IN ('Concentrate','Mineral Mix') AND feed_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)"))['total'] ?? 0;
$total = $roughage + $concentrate;
$roughage_pct = $total > 0 ? round(($roughage/$total)*100,1) : 0;
$concentrate_pct = $total > 0 ? round(($concentrate/$total)*100,1) : 0;
?>

<h2 class="text-3xl font-bold mb-4">Feed Ratio (Last 30 Days)</h2>
<div class="bg-white p-6 rounded-xl shadow max-w-md mb-6">
    <p>🌿 Roughage: <?= $roughage ?> kg (<?= $roughage_pct ?>%)</p>
    <p>💪 Concentrate: <?= $concentrate ?> kg (<?= $concentrate_pct ?>%)</p>
    <p class="mt-2 text-sm text-gray-500">Ideal for dairy cow: 60-70% roughage, 30-40% concentrate.</p>
</div>

<h3 class="text-xl font-semibold mb-2">Per Animal Ratio</h3>
<?php
$animals = mysqli_query($conn, "SELECT id, name FROM animals");
while($a = mysqli_fetch_assoc($animals)):
    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) AS total FROM daily_feeding WHERE animal_id=".$a['id']." AND food_type IN ('Grass','Hay','Silage') AND feed_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)"))['total'] ?? 0;
    $c = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) AS total FROM daily_feeding WHERE animal_id=".$a['id']." AND food_type IN ('Concentrate','Mineral Mix') AND feed_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)"))['total'] ?? 0;
    $t = $r + $c;
    $rp = $t > 0 ? round(($r/$t)*100,1) : 0;
    $cp = $t > 0 ? round(($c/$t)*100,1) : 0;
?>
<div class="bg-white p-4 rounded-xl shadow mb-2 max-w-md">
    <p><b><?= $a['name'] ?></b> – Roughage <?= $rp ?>% | Concentrate <?= $cp ?>%</p>
</div>
<?php endwhile; ?>
<?php include '../layout/footer.php'; ?>