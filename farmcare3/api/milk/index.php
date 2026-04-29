<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: ../index.php");
require '../db.php';
include '../layout/header.php';
include '../layout/sidebar.php';

if(isset($_GET['delete'])){
    mysqli_query($conn, "DELETE FROM milk_records WHERE id=".$_GET['delete']);
    header("Location: index.php");
    exit;
}
?>

<h2 class="text-3xl font-bold mb-6">Milk Production History</h2>
<a href="add.php" class="bg-emerald-600 text-white px-4 py-2 rounded-lg inline-block mb-4">+ Add Record</a>

<?php
$records = mysqli_query($conn, "SELECT mr.*, a.name FROM milk_records mr JOIN animals a ON mr.animal_id = a.id ORDER BY mr.date DESC");
if(mysqli_num_rows($records) > 0):
?>
<div class="bg-white rounded-xl shadow overflow-hidden mb-6">
    <table class="w-full">
        <thead class="bg-gray-100">
            <tr><th class="p-3">Animal</th><th class="p-3">Date</th><th class="p-3">Liters</th><th class="p-3">Action</th></tr>
        </thead>
        <tbody>
            <?php while($r = mysqli_fetch_assoc($records)): ?>
            <tr class="border-t">
                <td class="p-3"><?= $r['name'] ?></td>
                <td class="p-3"><?= $r['date'] ?></td>
                <td class="p-3"><?= $r['quantity'] ?> L</td>
                <td class="p-3"><a href="?delete=<?= $r['id'] ?>" class="text-red-600" onclick="return confirm('Delete?')">Delete</a></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<!-- Chart for last 7 days -->
<?php
$chart_labels = [];
$chart_data = [];
for($i=6; $i>=0; $i--){
    $date = date('Y-m-d', strtotime("-$i days"));
    $chart_labels[] = date('D', strtotime($date));
    $sum = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) as total FROM milk_records WHERE date='$date'"))['total'] ?? 0;
    $chart_data[] = $sum;
}
?>
<div class="bg-white p-6 rounded-xl shadow">
    <canvas id="milkChart"></canvas>
</div>

<script>
new Chart(document.getElementById('milkChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($chart_labels) ?>,
        datasets: [{
            label: 'Total Liters',
            data: <?= json_encode($chart_data) ?>,
            backgroundColor: '#10B981'
        }]
    }
});
</script>
<?php include '../layout/footer.php'; ?>