<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: index.php");
require 'db.php';
include 'layout/header.php';
include 'layout/sidebar.php';

// Stats
$total_animals = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM animals"))['cnt'];
$total_milk_today = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) as total FROM milk_records WHERE date = CURDATE()"))['total'] ?? 0;
$healthy = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM animals WHERE status='Healthy'"))['cnt'];

// Health alerts (last check > 7 days or never)
$alerts = [];
$res = mysqli_query($conn, "SELECT * FROM animals WHERE last_check IS NULL OR last_check < DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
while($row = mysqli_fetch_assoc($res)) {
    $alerts[] = $row;
}

// Milk chart (last 7 days)
$chart_labels = [];
$chart_data = [];
for($i=6; $i>=0; $i--){
    $date = date('Y-m-d', strtotime("-$i days"));
    $chart_labels[] = date('D', strtotime($date));
    $sum = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) as total FROM milk_records WHERE date='$date'"))['total'] ?? 0;
    $chart_data[] = $sum;
}
?>

<h2 class="text-3xl font-bold text-gray-800 mb-6">Welcome, <?= $_SESSION['user']['name'] ?> 👋</h2>

<!-- Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow">
        <p class="text-gray-500">Total Animals</p>
        <p class="text-3xl font-bold text-emerald-700"><?= $total_animals ?></p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow">
        <p class="text-gray-500">Milk Today</p>
        <p class="text-3xl font-bold text-blue-600"><?= $total_milk_today ?> L</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow">
        <p class="text-gray-500">Healthy</p>
        <p class="text-3xl font-bold text-green-600"><?= $healthy ?></p>
    </div>
</div>

<!-- Alerts -->
<?php if(count($alerts) > 0): ?>
<div class="bg-red-50 border border-red-200 p-4 rounded-xl mb-6">
    <h3 class="text-red-800 font-semibold mb-2">⚠️ Health Check Required</h3>
    <ul class="list-disc ml-6 text-red-700">
        <?php foreach($alerts as $a): ?>
        <li><?= $a['name'] ?> (<?= $a['type'] ?>) - Last check: <?= $a['last_check'] ?? 'Never' ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<!-- Milk chart -->
<div class="bg-white p-6 rounded-xl shadow">
    <h3 class="text-xl font-semibold mb-4">Milk Production (Last 7 Days)</h3>
    <canvas id="milkChart" height="80"></canvas>
</div>

<script>
new Chart(document.getElementById('milkChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($chart_labels) ?>,
        datasets: [{
            label: 'Liters',
            data: <?= json_encode($chart_data) ?>,
            borderColor: '#059669',
            backgroundColor: 'rgba(5,150,105,0.1)',
            fill: true
        }]
    }
});
</script>

<?php include 'layout/footer.php'; ?>